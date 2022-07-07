import { BadRequestException, ForbiddenException, Injectable, InternalServerErrorException } from '@nestjs/common';
import { PrismaService } from 'src/prisma/prisma.service';
import { RegisterDto, LoginDto } from './dto';
import * as bcrypt from 'bcrypt';
import { JwtService } from '@nestjs/jwt';
import { ConfigService } from '@nestjs/config';
import { Tokens } from './types';

@Injectable()
export class AuthService {
  constructor(private prisma: PrismaService, private jwt: JwtService, private config: ConfigService) {}

  public async register(body: RegisterDto): Promise<Tokens> {
    // Check for already existing user
    const existingUser = await this.prisma['User'].findFirst({
      where: {
        email: body.email,
      },
    });

    // If user exists throw error
    if (existingUser) {
      throw new BadRequestException('Email address is already taken');
    }

    // Hash the password
    const hashedPassword = await this.hashData(body.password);

    // Create the user
    const user = await this.prisma['User'].create({
      data: {
        email: body.email,
        password: hashedPassword,
        firstName: body.firstName ? body.firstName : null,
        lastName: body.lastName ? body.lastName : null,
        age: body.age ? body.age : null,
      },
    });

    // For some reasons failed to create user
    if (!user) {
      throw new InternalServerErrorException('Something went wrong. please try again later...');
    }

    // Generate tokens
    const tokens = await this.generateTokens(user.id, user.email);

    // Update the refresh token in db
    await this.updateRefreshToken(user.id, tokens.refresh_token);

    // Return tokens
    return tokens;
  }

  public async login(body: LoginDto): Promise<Tokens> {
    // Check for existing user
    const user = await this.prisma['User'].findFirst({
      where: {
        email: body.email,
      },
    });

    // If couldn't find the user throw an error
    if (!user) {
      throw new BadRequestException('Invalid email address or password');
    }

    // User exists, compare the password
    const matchingPassword = await bcrypt.compare(body.password, user.password);

    // If password does not match throw an error
    if (!matchingPassword) {
      throw new BadRequestException('Invalid email address or password');
    }

    // Generate tokens
    const tokens = await this.generateTokens(user.id, user.email);

    // Update the refresh token in db
    await this.updateRefreshToken(user.id, tokens.refresh_token);

    // Return tokens
    return tokens;
  }

  public async logout(userId: number): Promise<boolean> {
    // Find user by provided id and by the refreshToken where is not null and set it to null
    await this.prisma['User'].updateMany({
      where: {
        id: userId,
        refreshToken: {
          not: null,
        },
      },
      data: {
        refreshToken: null,
      },
    });

    return true;
  }

  public async refresh(userId: number, refreshToken: string) {
    // Find the user
    const user = await this.prisma['User'].findFirst({
      where: {
        id: userId,
      },
    });

    // If user not found or the token is already null
    if (!user || !user.refreshToken) {
      throw new ForbiddenException('Access denied');
    }

    // Compare tokens
    const refreshTokenMatches = await this.compareToken(refreshToken, user.refreshToken);

    if (!refreshTokenMatches) {
      throw new ForbiddenException('Access denied');
    }

    // Generate tokens
    const tokens = await this.generateTokens(user.id, user.email);

    // Update the refresh token in db
    await this.updateRefreshToken(user.id, tokens.refresh_token);

    // Return tokens
    return tokens;
  }

  // Helpers
  private async hashData(data: string): Promise<string> {
    return await bcrypt.hash(data, 10);
  }

  private async compareToken(providedToken: string, actualToken: string): Promise<boolean> {
    return await bcrypt.compare(providedToken, actualToken);
  }

  private async generateTokens(userId: number, email: string): Promise<Tokens> {
    const [at, rt] = await Promise.all([
      this.jwt.signAsync(
        {
          id: userId,
          email,
        },
        {
          secret: this.config.get('ACCESS_TOKEN'),
          expiresIn: 60 * 15, // 15min
        },
      ),
      this.jwt.signAsync(
        {
          id: userId,
          email,
        },
        {
          secret: this.config.get('REFRESH_TOKEN'),
          expiresIn: 60 * 60 * 24 * 7, // 1week
        },
      ),
    ]);

    return {
      access_token: at,
      refresh_token: rt,
    };
  }

  private async updateRefreshToken(userId: number, refreshToken: string): Promise<void> {
    // Hash the token
    const hashedToken = await this.hashData(refreshToken);

    // Update field with token in db
    await this.prisma['User'].updateMany({
      where: {
        id: userId,
      },
      data: {
        refreshToken: hashedToken,
      },
    });
  }
}

import { BadRequestException, Injectable, InternalServerErrorException } from '@nestjs/common';
import { PrismaService } from 'src/prisma/prisma.service';
import { RegisterDto } from './dto';
import * as bcrypt from 'bcrypt';
import { JwtService } from '@nestjs/jwt';

@Injectable()
export class AuthService {
  constructor(private prisma: PrismaService, private jwt: JwtService) {}

  public async register(body: RegisterDto) {
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
    const hashedPassword = await this.generatePasswordHash(body.password);

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
  }

  public async login() {}

  public async logout() {}

  private async generatePasswordHash(password): Promise<string> {
    return await bcrypt.hash(password, 10);
  }

  private async generateTokens(userId: number, email: string): Promise<string> {
    return '';
  }
}

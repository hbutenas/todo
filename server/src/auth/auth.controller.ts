import { Body, Controller, HttpCode, HttpStatus, Post, UseGuards } from '@nestjs/common';
import { AuthService } from './auth.service';
import { GetCurrentUser, Public } from './decorators';
import { GetCurrentUserId } from './decorators/get-current-user-id.decorator';
import { RegisterDto, LoginDto } from './dto';
import { RefreshTokenGuard } from './guards';

@Controller('api/v1/auth')
export class AuthController {
  constructor(private authService: AuthService) {}

  @Public()
  @Post('register')
  @HttpCode(HttpStatus.CREATED)
  public async register(@Body() body: RegisterDto) {
    return this.authService.register(body);
  }

  @Public()
  @Post('login')
  @HttpCode(HttpStatus.OK)
  public async login(@Body() body: LoginDto) {
    return this.authService.login(body);
  }

  @Post('logout')
  @HttpCode(HttpStatus.OK)
  public async logout(@GetCurrentUserId() userId: number) {
    return this.authService.logout(userId);
  }

  @Public()
  @UseGuards(RefreshTokenGuard)
  @Post('refresh')
  @HttpCode(HttpStatus.OK)
  public async refresh(@GetCurrentUserId() userId: number, @GetCurrentUser('refreshToken') refreshToken: string) {
    return this.authService.refresh(userId, refreshToken);
  }
}

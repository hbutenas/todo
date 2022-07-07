import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { PrismaModule } from './prisma/prisma.module';
import { AuthModule } from './auth/auth.module';
import { APP_GUARD } from '@nestjs/core';
import { AccessTokenGuard } from './auth/guards';
import { TodoModule } from './todo/todo.module';

@Module({
  imports: [ConfigModule.forRoot({ isGlobal: true }), PrismaModule, AuthModule, TodoModule],
  providers: [
    {
      provide: APP_GUARD,
      useClass: AccessTokenGuard, // If there is no @Public() decorator it will require a access token on entry
    },
  ],
})
export class AppModule {}

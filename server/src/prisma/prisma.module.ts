import { Global, Module } from '@nestjs/common';
import { PrismaService } from './prisma.service';

@Global() // Using Global() to avoid importing it to every single module
@Module({
  providers: [PrismaService],
  exports: [PrismaService],
})
export class PrismaModule {}

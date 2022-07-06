import { IsEmail, IsNotEmpty, IsInt, IsOptional, IsString, Min } from 'class-validator';

export class RegisterDto {
  @IsEmail()
  @IsNotEmpty()
  email: string;

  @IsString()
  @IsNotEmpty()
  password: string;

  @IsString()
  @IsOptional()
  firstName: string;

  @IsString()
  @IsOptional()
  lastName: string;

  @IsInt()
  @Min(10)
  @IsOptional()
  age: number;
}

import { Body, Controller, Delete, Get, HttpCode, HttpStatus, Param, Patch, Post } from '@nestjs/common';
import { GetCurrentUserId } from 'src/auth/decorators';
import { CreateTodoDto } from './dto';
import { TodoService } from './todo.service';
import { Todos } from './types';

@Controller('api/v1/todo')
export class TodoController {
  constructor(private todoService: TodoService) {}

  @HttpCode(HttpStatus.CREATED)
  @Post()
  public async create(@Body() body: CreateTodoDto, @GetCurrentUserId() userId: number): Promise<Todos> {
    return this.todoService.create(body, userId);
  }

  @HttpCode(HttpStatus.OK)
  @Get()
  public async getAll(@GetCurrentUserId() userId: number): Promise<Todos> {
    return this.todoService.getAll(userId);
  }

  @HttpCode(HttpStatus.OK)
  @Get(':id')
  public async getOne(@GetCurrentUserId() userId: number, @Param('id') todoId: string): Promise<Todos> {
    return this.todoService.getOne(userId, parseInt(todoId));
  }

  @HttpCode(HttpStatus.OK)
  @Patch(':id')
  public async update(@Body() body: CreateTodoDto, @GetCurrentUserId() userId: number, @Param('id') todoId: string): Promise<Todos> {
    return this.todoService.update(body, userId, parseInt(todoId));
  }

  @HttpCode(HttpStatus.OK)
  @Delete(':id')
  public async delete(@GetCurrentUserId() userId: number, @Param('id') todoId: string): Promise<string> {
    return this.todoService.delete(userId, parseInt(todoId));
  }
}

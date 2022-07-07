import { BadRequestException, Injectable, InternalServerErrorException } from '@nestjs/common';
import { PrismaService } from 'src/prisma/prisma.service';
import { CreateTodoDto } from './dto';
import { Todos } from './types';

@Injectable()
export class TodoService {
  constructor(private prisma: PrismaService) {}

  public async create(body: CreateTodoDto, userId: number): Promise<Todos> {
    // Create a todo
    const todo = await this.prisma['Todo'].create({
      data: {
        userId,
        task: body.task,
        description: body.description,
      },
    });

    return todo;
  }

  public async getAll(userId: number): Promise<Todos> {
    // Receive all tasks which own provided userId
    const todos = await this.prisma['Todo'].findMany({
      where: {
        userId,
      },
    });

    return todos;
  }

  public async getOne(userId: number, todoId: number): Promise<Todos> {
    // Receive a todo by todo id and return todos if it's request's author
    const todo = await this.prisma['Todo'].findFirst({
      where: {
        AND: [{ id: todoId, userId }],
      },
    });

    // Provided incorrect id or is trying to check others task
    if (!todo) {
      throw new BadRequestException(`Task with id ${todoId} does not exists`);
    }

    return todo;
  }

  public async update(body: CreateTodoDto, userId: number, todoId: number): Promise<Todos> {
    // Find the provided task by task id
    const todo = await this.prisma['Todo'].findFirst({
      where: {
        AND: [{ id: todoId, userId }],
      },
    });

    // Provided incorrect id or is trying to check others task
    if (!todo) {
      throw new BadRequestException(`Task with id ${todoId} does not exists`);
    }

    const updateTodo = await this.prisma['Todo'].update({
      where: {
        id: todoId,
      },
      data: {
        task: body.task && body.task,
        description: body.description && body.description,
      },
    });

    return updateTodo;
  }

  public async delete(userId: number, todoId: number): Promise<string> {
    // Find the provided task by task id
    const todo = await this.prisma['Todo'].findFirst({
      where: {
        AND: [{ id: todoId, userId }],
      },
    });

    // Provided incorrect id or is trying to check others task
    if (!todo) {
      throw new BadRequestException(`Task with id ${todoId} does not exists`);
    }

    const deletedTodo = await this.prisma['Todo'].delete({
      where: {
        id: todoId,
      },
    });

    if (!deletedTodo) {
      throw new InternalServerErrorException('Something went wrong... Please try again later');
    }

    return `Successfully deleted todo with id ${todoId}`;
  }
}

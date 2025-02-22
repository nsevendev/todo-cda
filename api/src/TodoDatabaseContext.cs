using Microsoft.EntityFrameworkCore;

namespace api;

public class TodoDatabaseContext(DbContextOptions<TodoDatabaseContext> options) : DbContext(options)
{
}
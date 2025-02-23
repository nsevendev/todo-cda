using Microsoft.EntityFrameworkCore;

namespace app.src.Infrastructure.Databases;

public class TodoDatabaseContext(DbContextOptions<TodoDatabaseContext> options) : DbContext(options)
{
}
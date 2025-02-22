using System;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Logging;
using Microsoft.EntityFrameworkCore;
using Npgsql.EntityFrameworkCore.PostgreSQL;

using api;

var builder = WebApplication.CreateBuilder(args);
builder.Configuration.AddEnvironmentVariables();
var environment = builder.Configuration["ASPNETCORE_ENVIRONMENT"] ?? "Non défini";
var connectionString = builder.Configuration.GetConnectionString("DefaultConnection");

builder.Services.AddDbContext<TodoDatabaseContext>(options =>
    options.UseNpgsql(connectionString)
);

var app = builder.Build();
var logger = app.Services.GetRequiredService<ILogger<Program>>();

logger.LogInformation("🚀 Démarrage de l'application");
logger.LogInformation("🌿 Environnement détecté : {environment}", environment);

app.MapGet("/", () => "Hello Worldssss!");

app.Run();
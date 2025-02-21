using System;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Logging;

var builder = WebApplication.CreateBuilder(args);
var environment = builder.Configuration["ASPNETCORE_ENVIRONMENT"] ?? "Non défini";
var app = builder.Build();
var logger = app.Services.GetRequiredService<ILogger<Program>>();

logger.LogInformation("🚀 Démarrage de l'application");
logger.LogInformation("🌿 Environnement détecté : {environment}", environment);

app.MapGet("/", () => "Hello Worldssss!");

app.Run();
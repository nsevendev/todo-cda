FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build-dev
WORKDIR /app/api
COPY . .
RUN dotnet restore --force

FROM mcr.microsoft.com/dotnet/sdk:9.0 AS dev
WORKDIR /app/api
COPY --from=build-dev /app/api .
RUN dotnet tool install --global dotnet-ef
ENV PATH="/root/.dotnet/tools:$PATH"
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["dotnet", "watch", "run"]

FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build-prod
WORKDIR /app/api
COPY . .
RUN dotnet restore --force 
RUN dotnet publish -c Release -o /app/api/publish

FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS prod
WORKDIR /app/api
COPY --from=build-prod /app/api/publish .
RUN dotnet tool install --global dotnet-ef
ENV PATH="/root/.dotnet/tools:$PATH"
CMD ["dotnet", "$(ls *.dll)"]

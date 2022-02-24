## docker-compose (for example)
```
...
  helium-exporter:
    image: ghcr.io/tischi86/helium_exporter:latest
    restart: unless-stopped
    ports:
      - "8000:8000"
```
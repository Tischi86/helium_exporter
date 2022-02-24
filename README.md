## docker-compose (for example)
```
...
  helium-exporter:
    image: ghcr.io/tischi86/helium_exporter:latest
    restart: unless-stopped
    ports:
      - "8000:8000"
    environment:
      HOTSPOT_ADDRESSES: "id1,id2,id3..."
```
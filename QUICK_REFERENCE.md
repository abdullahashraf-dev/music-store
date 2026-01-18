# Music Store API - Quick Reference

## File Structure

```
app/
├── Core/
│   ├── ApiResponse.php        # Response formatting class
│   └── Result.php             # Result pattern for operations
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── AlbumController.php
│   │   ├── SongController.php
│   │   └── ArtistController.php
│   ├── Requests/Api/
│   │   ├── RegisterRequest.php
│   │   ├── LoginRequest.php
│   │   ├── CreateAlbumRequest.php
│   │   ├── CreateSongRequest.php
│   │   └── CreateArtistRequest.php
│   └── Resources/
│       ├── UserResource.php
│       ├── ArtistResource.php
│       ├── ArtistDetailResource.php
│       ├── AlbumResource.php
│       └── SongResource.php
├── Models/
│   ├── User.php
│   ├── Album.php
│   ├── Song.php
│   └── Artist.php
├── Repositories/
│   ├── UserRepository.php
│   ├── AlbumRepository.php
│   ├── SongRepository.php
│   └── ArtistRepository.php
├── Services/
│   ├── AuthService.php
│   ├── AlbumService.php
│   ├── SongService.php
│   └── ArtistService.php
└── Transformers/
    └── Transformer.php
```

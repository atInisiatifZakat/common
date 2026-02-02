# Inisiatif Common Package

Common package yang digunakan di Inisiatif Zakat Indonesia.

## Requirements

- PHP 8.2, 8.3, atau 8.4
- Laravel 10.x, 11.x, atau 12.x

## Installation

```bash
composer require inisiatif/common
```

## Configuration

Package ini akan otomatis register service provider melalui Laravel package discovery.

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Inisiatif\Package\Common\Providers\CommonServiceProvider"
```

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `COMMON_BRANCH_TABLE_NAME` | `branches` | Nama tabel untuk model Branch |

## Features

### Models

#### Branch
Model untuk menyimpan data cabang/branch.

```php
use Inisiatif\Package\Common\Models\Branch;

$branch = Branch::find($id);
```

### Concerns (Traits)

#### UuidPrimaryKey
Trait untuk menggunakan UUID sebagai primary key.

```php
use Inisiatif\Package\Common\Concerns\UuidPrimaryKey;

class MyModel extends Model
{
    use UuidPrimaryKey;
}
```

#### IntegerPrimaryKey
Trait untuk model dengan integer primary key.

#### HasBranch
Trait untuk model yang memiliki relasi ke Branch.

```php
use Inisiatif\Package\Common\Concerns\HasBranch;

class MyModel extends Model
{
    use HasBranch;
}
```

#### TaggableCacheAware
Trait untuk repository yang membutuhkan cache dengan tagging.

#### EloquentAwareRepository
Trait untuk repository pattern dengan Eloquent.

### Contracts (Interfaces)

| Interface | Description |
|-----------|-------------|
| `ResourceInterface` | Marker interface untuk model/resource |
| `ModelRepositoryInterface` | Interface untuk operasi repository model |
| `EloquentAwareRepositoryInterface` | Interface untuk repository dengan Eloquent |
| `TaggableCacheAwareInterface` | Interface untuk cache-aware repositories |
| `HasBranchInterface` | Interface untuk model yang memiliki branch |
| `Notable` | Interface untuk model yang dapat memiliki catatan |

### Abstract Classes

#### AbstractRepository
Base class untuk implementasi repository pattern.

```php
use Inisiatif\Package\Common\Abstracts\AbstractRepository;
use Inisiatif\Package\Common\Contracts\ResourceInterface;

class UserRepository extends AbstractRepository
{
    protected $model = User::class;
}

// Model harus implement ResourceInterface
class User extends Model implements ResourceInterface
{
    use UuidPrimaryKey;
}
```

### Exceptions

| Exception | Description |
|-----------|-------------|
| `DomainException` | Base exception untuk domain errors |
| `DomainActionException` | Exception untuk action yang tidak valid |
| `DomainModelExistException` | Exception ketika model sudah ada |
| `DomainModelNotExistException` | Exception ketika model tidak ditemukan |

## Migrations

Package ini menyediakan migration untuk tabel `branches`. Untuk menonaktifkan migration:

```php
use Inisiatif\Package\Common\Common;

// Di AppServiceProvider boot method
Common::ignoreMigrations();
```

## Development

### Running Tests

```bash
composer test
```

### Static Analysis

```bash
composer analyse
```

### Code Formatting

```bash
composer format
```

## License

Proprietary - Inisiatif Zakat Indonesia

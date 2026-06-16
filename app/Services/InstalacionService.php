<?php

namespace App\Services;

use App\Repositories\Contracts\InstalacionRepositoryInterface;
use App\Models\Instalacion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InstalacionService
{
    public function __construct(private InstalacionRepositoryInterface $repo) {}

    public function listar(int $perPage = 15): LengthAwarePaginator { return $this->repo->paginate($perPage); }
    public function ver(int $tramoId, int $materialId, int $almacenId): ?Instalacion { return $this->repo->find($tramoId, $materialId, $almacenId); }
    // Alias usado por el nuevo controlador
    public function verComposite(int $tramoId, int $materialId, int $almacenId): ?Instalacion { return $this->ver($tramoId, $materialId, $almacenId); }

    public function crear(array $data): Instalacion { return $this->repo->create($data); }

    public function actualizar(int $tramoId, int $materialId, int $almacenId, array $data): ?Instalacion { return $this->repo->updateComposite($tramoId, $materialId, $almacenId, $data); }
    // Alias usado por el nuevo controlador
    public function actualizarComposite(int $tramoId, int $materialId, int $almacenId, array $data): ?Instalacion { return $this->actualizar($tramoId, $materialId, $almacenId, $data); }

    public function eliminarComposite(int $tramoId, int $materialId, int $almacenId): bool { return $this->repo->deleteComposite($tramoId, $materialId, $almacenId); }
}

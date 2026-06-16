<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Almacen;
use App\Models\Ciudad;

class AlmacenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para verificar que se puede crear un almacén correctamente.
     */
    public function test_puede_crear_almacen_con_datos_validos(): void
    {
        // Crear una ciudad primero (requisito para el almacén)
        $ciudad = Ciudad::create([
            'nombre' => 'Madrid'
        ]);

        // Datos del almacén
        $almacenData = [
            'nombre' => 'Almacén Central',
            'ciudad' => $ciudad->id_ciudad
        ];

        // Hacer la petición POST a la API
        $response = $this->postJson('/api/v1/almacenes', $almacenData);

        // Verificar que la respuesta es exitosa (201)
        $response->assertStatus(201)
                 ->assertJson([
                     'ok' => true,
                     'mensaje' => 'Almacén creado'
                 ])
                 ->assertJsonStructure([
                     'ok',
                     'mensaje',
                     'datos' => [
                         'id_almacen',
                         'nombre',
                         'id_ciudad'
                     ]
                 ]);

        // Verificar que el almacén existe en la base de datos
        $this->assertDatabaseHas('almacenes', [
            'nombre' => 'Almacén Central',
            'id_ciudad' => $ciudad->id_ciudad
        ]);
    }

    /**
     * Test para verificar la validación de campos requeridos.
     */
    public function test_falla_al_crear_almacen_sin_nombre(): void
    {
        $ciudad = Ciudad::create(['nombre' => 'Barcelona']);

        $almacenData = [
            'ciudad' => $ciudad->id_ciudad
            // 'nombre' falta intencionalmente
        ];

        $response = $this->postJson('/api/v1/almacenes', $almacenData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }

    /**
     * Test para verificar la validación del campo ciudad.
     */
    public function test_falla_al_crear_almacen_sin_ciudad(): void
    {
        $almacenData = [
            'nombre' => 'Almacén Sin Ciudad'
            // 'ciudad' falta intencionalmente
        ];

        $response = $this->postJson('/api/v1/almacenes', $almacenData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['ciudad']);
    }

    /**
     * Test para verificar que se puede listar almacenes.
     */
    public function test_puede_listar_almacenes(): void
    {
        $ciudad = Ciudad::create(['nombre' => 'Valencia']);

        // Crear varios almacenes
        Almacen::create([
            'nombre' => 'Almacén 1',
            'id_ciudad' => $ciudad->id_ciudad
        ]);

        Almacen::create([
            'nombre' => 'Almacén 2',
            'id_ciudad' => $ciudad->id_ciudad
        ]);

        $response = $this->getJson('/api/v1/almacenes');

        $response->assertStatus(200)
                 ->assertJson([
                     'ok' => true,
                     'mensaje' => 'Listado de almacenes obtenido'
                 ])
                 ->assertJsonCount(2, 'datos');
    }

    /**
     * Test para verificar que se puede actualizar un almacén.
     */
    public function test_puede_actualizar_almacen(): void
    {
        $ciudad1 = Ciudad::create(['nombre' => 'Sevilla']);
        $ciudad2 = Ciudad::create(['nombre' => 'Bilbao']);

        $almacen = Almacen::create([
            'nombre' => 'Almacén Original',
            'id_ciudad' => $ciudad1->id_ciudad
        ]);

        $datosActualizados = [
            'nombre' => 'Almacén Actualizado',
            'ciudad' => $ciudad2->id_ciudad
        ];

        $response = $this->putJson("/api/v1/almacenes/{$almacen->id_almacen}", $datosActualizados);

        $response->assertStatus(200)
                 ->assertJson([
                     'ok' => true,
                     'mensaje' => 'Almacén actualizado'
                 ]);

        // Verificar que los datos se actualizaron en la base de datos
        $this->assertDatabaseHas('almacenes', [
            'id_almacen' => $almacen->id_almacen,
            'nombre' => 'Almacén Actualizado',
            'id_ciudad' => $ciudad2->id_ciudad
        ]);
    }

    /**
     * Test para verificar que se puede eliminar un almacén.
     */
    public function test_puede_eliminar_almacen(): void
    {
        $ciudad = Ciudad::create(['nombre' => 'Zaragoza']);

        $almacen = Almacen::create([
            'nombre' => 'Almacén a Eliminar',
            'id_ciudad' => $ciudad->id_ciudad
        ]);

        $response = $this->deleteJson("/api/v1/almacenes/{$almacen->id_almacen}");

        $response->assertStatus(200)
                 ->assertJson([
                     'ok' => true,
                     'mensaje' => 'Almacén eliminado'
                 ]);

        // Verificar que el almacén ya no existe en la base de datos
        $this->assertDatabaseMissing('almacenes', [
            'id_almacen' => $almacen->id_almacen
        ]);
    }

    /**
     * Test para verificar que falla al eliminar un almacén inexistente.
     */
    public function test_falla_al_eliminar_almacen_inexistente(): void
    {
        $response = $this->deleteJson('/api/v1/almacenes/9999');

        $response->assertStatus(404)
                 ->assertJson([
                     'ok' => false,
                     'mensaje' => 'Almacén no encontrado'
                 ]);
    }

    /**
     * Test para verificar que los almacenes se listan con su relación de ciudad.
     */
    public function test_almacenes_incluyen_relacion_ciudad(): void
    {
        $ciudad = Ciudad::create(['nombre' => 'Málaga']);

        Almacen::create([
            'nombre' => 'Almacén con Ciudad',
            'id_ciudad' => $ciudad->id_ciudad
        ]);

        $response = $this->getJson('/api/v1/almacenes');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'datos' => [
                         '*' => [
                             'id_almacen',
                             'nombre',
                             'id_ciudad',
                             'ciudad_ref' => [
                                 'id_ciudad',
                                 'nombre'
                             ]
                         ]
                     ]
                 ]);
    }
}

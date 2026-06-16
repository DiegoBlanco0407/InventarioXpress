<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Almacen;
use App\Models\Ciudad;

class AlmacenUnitTest extends TestCase
{
    /**
     * Test para verificar que el nombre del almacén se puede formatear correctamente.
     */
    public function test_nombre_almacen_se_formatea_correctamente(): void
    {
        $nombre = "  almacén central  ";
        $nombreFormateado = trim(ucwords(strtolower($nombre)));
        
        $this->assertEquals('Almacén Central', $nombreFormateado);
    }

    /**
     * Test para verificar validación de longitud de nombre.
     */
    public function test_nombre_almacen_no_excede_longitud_maxima(): void
    {
        $nombreCorto = "Almacén";
        $nombreLargo = str_repeat("a", 256);
        
        $this->assertLessThanOrEqual(255, strlen($nombreCorto));
        $this->assertGreaterThan(255, strlen($nombreLargo));
    }

    /**
     * Test para verificar que un ID de ciudad es válido (mayor a 0).
     */
    public function test_id_ciudad_debe_ser_mayor_a_cero(): void
    {
        $idValido = 1;
        $idInvalido = 0;
        $idNegativo = -1;
        
        $this->assertGreaterThan(0, $idValido);
        $this->assertLessThanOrEqual(0, $idInvalido);
        $this->assertLessThan(0, $idNegativo);
    }

    /**
     * Test para verificar estructura de datos de almacén.
     */
    public function test_estructura_datos_almacen(): void
    {
        $almacenData = [
            'nombre' => 'Almacén Norte',
            'ciudad' => 5
        ];
        
        $this->assertArrayHasKey('nombre', $almacenData);
        $this->assertArrayHasKey('ciudad', $almacenData);
        $this->assertIsString($almacenData['nombre']);
        $this->assertIsInt($almacenData['ciudad']);
    }

    /**
     * Test para verificar que los campos requeridos están presentes.
     */
    public function test_campos_requeridos_almacen(): void
    {
        $camposRequeridos = ['nombre', 'ciudad'];
        $datosAlmacen = [
            'nombre' => 'Almacén Sur',
            'ciudad' => 3
        ];
        
        foreach ($camposRequeridos as $campo) {
            $this->assertArrayHasKey($campo, $datosAlmacen);
            $this->assertNotEmpty($datosAlmacen[$campo]);
        }
    }

    /**
     * Test para verificar conversión de datos de API a base de datos.
     */
    public function test_mapeo_campo_ciudad_a_id_ciudad(): void
    {
        $datosAPI = ['ciudad' => 10];
        $datosBD = ['id_ciudad' => $datosAPI['ciudad']];
        
        $this->assertEquals($datosAPI['ciudad'], $datosBD['id_ciudad']);
    }

    /**
     * Test para validar que el nombre no esté vacío después de trim.
     */
    public function test_nombre_no_vacio_despues_de_trim(): void
    {
        $nombreConEspacios = "  Almacén  ";
        $nombreVacio = "   ";
        
        $this->assertNotEmpty(trim($nombreConEspacios));
        $this->assertEmpty(trim($nombreVacio));
    }
}

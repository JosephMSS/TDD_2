## Relaciones multiples
### Pruebas unitarias
> Vamos a probar primero un modelo, por lo tanto vamos a ejecutar pruebas a un modelo, ya que sol queremos ver el resutado dee un metodo.

Error | Solucion
---|---
`Call to a member function connection() on null` | Esto sucede debido a que estamos empleando metodos propios de laravel  por lo que debemos usar el test case que porvee laravel y no el de phpunit por lo que usamos la clase `use Tests\TestCase;` en vez de `use PHPUnit\Framework\TestCase;`
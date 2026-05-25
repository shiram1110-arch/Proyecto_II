<?php 
namespace App\Http\Middleware; 
use Closure; 
use Illuminate\Support\Facades\App; 
class SetLocaleFromHeader 
{ 
    public function handle($request, Closure $next) 
    { 
// Obtiene el idioma del encabezado 
$locale = $request->header('Accept-Language', 'es'); 
// Extrae solo los primeros 2 caracteres 
// Ejemplo: es-CR -> es 
$locale = substr($locale, 0, 2); 
// Verifica idiomas permitidos 
if (in_array($locale, ['es', 'en'])) { 
App::setLocale($locale); 
} else { 
App::setLocale('es'); 
} 
return $next($request); 
} 
}
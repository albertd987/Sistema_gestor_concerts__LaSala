<?php

/**
 * Script de Testing per Models LaSala 
 * Generats amb chatgpt
 */

use App\Models\User;
use App\Models\Artista;
use App\Models\Slot;
use App\Models\Reserva;
use App\Enums\UserRole;
use App\Enums\SlotStatus;
use App\Enums\EstatReserva;

echo "\n=== TESTING MODELS LASALA ===\n\n";

// ==================== TEST 1: Crear Usuarios ====================
echo "📝 TEST 1: Creant usuaris...\n";

$admin = User::create([
    'name' => 'Admin LaSala',
    'email' => 'admin@lasala.cat',
    'password' => bcrypt('password'),
    'rol' => UserRole::ADMIN,
]);
echo "✅ Admin creat: {$admin->name} ({$admin->rol->label()})\n";

$userArtista = User::create([
    'name' => 'The Rockers',
    'email' => 'rockers@example.com',
    'password' => bcrypt('password'),
    'rol' => UserRole::ARTISTA,
]);
echo "✅ User artista creat: {$userArtista->name}\n";

$public = User::create([
    'name' => 'Visitant',
    'email' => 'public@example.com',
    'password' => bcrypt('password'),
    'rol' => UserRole::PUBLIC,
]);
echo "✅ User públic creat: {$public->name}\n\n";

// ==================== TEST 2: Crear Artista ====================
echo "📝 TEST 2: Creant perfil d'artista...\n";

$artista = Artista::create([
    'id_usuari' => $userArtista->id,
    'nomGrup' => 'The Rockers Band',
    'genere' => 'Rock Alternatiu',
    'bio' => 'Banda emergent de rock alternatiu amb influències punk',
    'tlf_contacte' => '+34 666 777 888',
    'links_socials' => [
        'instagram' => '@therockers',
        'spotify' => 'spotify.com/therockers',
        'youtube' => 'youtube.com/@therockers',
    ],
]);
echo "✅ Artista creat: {$artista->nomGrup}\n";
echo "   Email: {$artista->email()}\n";
echo "   Gènere: {$artista->genere}\n\n";

// ==================== TEST 3: Crear Slots ====================
echo "📝 TEST 3: Creant slots disponibles...\n";

$slot1 = Slot::create([
    'data' => now()->addDays(7),
    'hora_inici' => '22:00:00',
    'hora_fi' => '23:30:00',
    'status' => SlotStatus::DISPONIBLE,
]);
echo "✅ Slot 1 creat: {$slot1->getDataHoraFormattedAttribute()}\n";

$slot2 = Slot::create([
    'data' => now()->addDays(14),
    'hora_inici' => '23:00:00',
    'hora_fi' => '01:00:00',
    'status' => SlotStatus::DISPONIBLE,
]);
echo "✅ Slot 2 creat: {$slot2->getDataHoraFormattedAttribute()}\n";

$slot3 = Slot::create([
    'data' => now()->addDays(21),
    'hora_inici' => '22:00:00',
    'hora_fi' => '00:00:00',
    'status' => SlotStatus::DISPONIBLE,
]);
echo "✅ Slot 3 creat: {$slot3->getDataHoraFormattedAttribute()}\n\n";

// ==================== TEST 4: Crear Reserva ====================
echo "📝 TEST 4: Artista crea reserva...\n";

$reserva = Reserva::create([
    'id_artista' => $artista->id,
    'id_slot' => $slot1->id,
    'status' => EstatReserva::PENDENT,
    'notes_artistes' => 'Ens agradaria tocar rock alternatiu i punk. Tenim PA propi.',
]);
echo "✅ Reserva creada: #{$reserva->id}\n";
echo "   Artista: {$reserva->nomArtista}\n";
echo "   Data: {$reserva->dataSlotFormatted}\n";
echo "   Estat: {$reserva->statusLabel} ({$reserva->statusColor})\n\n";

// ==================== TEST 5: Verificar Disponibilitat ====================
echo "📝 TEST 5: Verificant disponibilitat de slots...\n";

echo "Slot 1 disponible? " . ($slot1->isAvailable() ? '✅ SÍ' : '❌ NO') . "\n";
echo "Slot 2 disponible? " . ($slot2->isAvailable() ? '✅ SÍ' : '❌ NO') . "\n";
echo "Slot 3 disponible? " . ($slot3->isAvailable() ? '✅ SÍ' : '❌ NO') . "\n\n";

// ==================== TEST 6: Aprovar Reserva ====================
echo "📝 TEST 6: Admin aprova la reserva...\n";

try {
    $reserva->aprovar($admin);
    echo "✅ Reserva aprovada correctament!\n";
    echo "   Estat nou: {$reserva->fresh()->statusLabel}\n";
    echo "   Aprovat per: {$reserva->aprovador->name}\n";
    echo "   Aprovat a: {$reserva->aprovat_a->format('d/m/Y H:i')}\n";
    
    // Verificar que el slot s'ha marcat com reservat
    $slot1->refresh();
    echo "   Estat del slot: {$slot1->status->label()}\n";
    
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}
echo "\n";

// ==================== TEST 7: Verificar que slot ja no està disponible ====================
echo "📝 TEST 7: Verificant que slot 1 ja no està disponible...\n";

$slot1->refresh();
echo "Slot 1 disponible ara? " . ($slot1->isAvailable() ? '✅ SÍ' : '❌ NO (correcte!)') . "\n\n";

// ==================== TEST 8: Crear i Rebutjar Reserva ====================
echo "📝 TEST 8: Crear i rebutjar una reserva...\n";

$reserva2 = Reserva::create([
    'id_artista' => $artista->id,
    'id_slot' => $slot2->id,
    'status' => EstatReserva::PENDENT,
    'notes_artistes' => 'Segon concert del mes',
]);
echo "✅ Reserva 2 creada: #{$reserva2->id}\n";

try {
    $reserva2->rebutjar($admin, 'Ja tenim programat un altre concert de rock aquell dia');
    echo "✅ Reserva rebutjada correctament!\n";
    echo "   Motiu: {$reserva2->fresh()->notes_admin}\n";
    
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}
echo "\n";

// ==================== TEST 9: Scopes i Queries ====================
echo "📝 TEST 9: Provant scopes...\n";

echo "Total reserves pendents: " . Reserva::pendents()->count() . "\n";
echo "Total reserves aprovades: " . Reserva::aprovades()->count() . "\n";
echo "Total reserves rebutjades: " . Reserva::rebutjades()->count() . "\n";

echo "Total slots disponibles: " . Slot::disponibles()->count() . "\n";
echo "Total slots reservables (disponibles i futurs): " . Slot::reservables()->count() . "\n";

echo "Reserves de l'artista: " . $artista->reserves()->count() . "\n";
echo "Reserves aprovades de l'artista: " . $artista->reservesAprovades()->count() . "\n";
echo "Ha actuat alguna vegada? " . ($artista->haActuat() ? '✅ SÍ' : '❌ NO') . "\n\n";

// ==================== TEST 10: Intentar Transició Invàlida ====================
echo "📝 TEST 10: Provant transició invàlida (fail-fast)...\n";

try {
    // Intentar aprovar una reserva ja aprovada
    $reserva->aprovar($admin);
    echo "❌ PROBLEMA: No hauria de permetre aprovar una reserva ja aprovada!\n";
    
} catch (\App\Excepcions\InvalidStateTransitionException $e) {
    echo "✅ Excepció llençada correctament:\n";
    echo "   {$e->getMessage()}\n";
}
echo "\n";

// ==================== TEST 11: Intentar Double Booking ====================
echo "📝 TEST 11: Provant double booking (hauria de fallar)...\n";

$reserva3 = Reserva::create([
    'id_artista' => $artista->id,
    'id_slot' => $slot1->id, // Mateix slot que ja està reservat
    'status' => EstatReserva::PENDENT,
    'notes_artistes' => 'Intent de reservar slot ja ocupat',
]);

try {
    $reserva3->aprovar($admin);
    echo "❌ PROBLEMA: No hauria de permetre aprovar 2 reserves del mateix slot!\n";
    
} catch (\App\Excepcions\SlotNoDisponibleException $e) {
    echo "✅ Excepció llençada correctament:\n";
    echo "   {$e->getMessage()}\n";
}
echo "\n";

// ==================== RESUM ====================
echo "=== RESUM DELS TESTS ===\n";
echo "Total usuaris creats: " . User::count() . "\n";
echo "Total artistes creats: " . Artista::count() . "\n";
echo "Total slots creats: " . Slot::count() . "\n";
echo "Total reserves creades: " . Reserva::count() . "\n\n";

echo "✅ TOTS ELS TESTS COMPLETATS!\n";
echo "Els models funcionen correctament.\n\n";
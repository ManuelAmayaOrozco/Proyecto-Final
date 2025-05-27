<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class UpdateDailyPost extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'daily:update-post';

    /**
     * La descripción del comando de consola.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente el post del día.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(): void
    {
        Post::where('dailyPost', true)->update(['dailyPost' => false]);

        $postIds = Post::pluck('id');

        if ($postIds->isNotEmpty()) {
            $randomPostId = $postIds->random();
            Post::find($randomPostId)?->update(['dailyPost' => true]);
            $this->info("✅ Post diario actualizado: ID $randomPostId");
        } else {
            $this->warn("⚠️ No hay posts disponibles.");
        }
    }
}
<?php

namespace App\Providers;



// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//gunakan gate untuk authorized user yang bisa ngubah indikator dan sasarannya sendiri
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //GATE TEMATIK
        Gate::define('modify-indikator-roadmap', function ($user, $indikator_roadmap) {
            return $user->instansi_id === $indikator_roadmap->sasaran_roadmap->instansi_id;
        });

        Gate::define('modify-sasaran-roadmap', function ($user, $sasaran_roadmap) {
            return $user->instansi_id === $sasaran_roadmap->instansi_id;
        });

        Gate::define('modify-permasalahan', function ($user, $permasalahan) {
            return $user->instansi_id === $permasalahan->indikator_roadmap->sasaran_roadmap->instansi_id;
        });

        Gate::define('modify-indikator', function ($user, $indikator) {
            return $user->instansi_id === $indikator->permasalahan->indikator_roadmap->sasaran_roadmap->instansi_id;
        });

        Gate::define('modify-rencana-aksi', function ($user, $rencana_aksi) {
            return $user->instansi_id === $rencana_aksi->indikator->permasalahan->indikator_roadmap->sasaran_roadmap->instansi_id;
        });
    }
}

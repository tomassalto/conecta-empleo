<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        // Si no tenés usuarios todavía, creamos uno empresa por defecto
        $company = User::firstOrCreate(
            ['email' => 'empresa@demo.com'],
            [
                'name' => 'Empresa Demo',
                'password' => bcrypt('password'),
                'role' => 'company'
            ]
        );

        $jobs = [
            [
                'title' => 'Desarrollador Laravel Senior',
                'seniority' => 'Senior',
                'type' => 'Full-time',
                'location' => 'Remoto',
                'salary_min' => 900000,
                'salary_max' => 1200000,
                'description' => 'Buscamos un desarrollador con experiencia sólida en Laravel y MySQL.',
            ],
            [
                'title' => 'Frontend Developer React',
                'seniority' => 'Semi Senior',
                'type' => 'Remoto',
                'location' => 'Neuquén, Argentina',
                'salary_min' => 600000,
                'salary_max' => 800000,
                'description' => 'Se busca frontend con experiencia en React, Tailwind y consumo de APIs REST.',
            ],
            [
                'title' => 'DevOps Junior',
                'seniority' => 'Junior',
                'type' => 'Part-time',
                'location' => 'Buenos Aires',
                'salary_min' => 400000,
                'salary_max' => 550000,
                'description' => 'Aprendé sobre CI/CD, Docker y despliegues automatizados.',
            ],
        ];

        foreach ($jobs as $job) {
            $company->jobs()->create($job);
        }
    }
}

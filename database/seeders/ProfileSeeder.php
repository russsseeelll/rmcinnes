<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\SkillGroup;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::query()->delete();
        Profile::create([
            'name' => 'Alex Example',
            'headline' => 'DevOps Engineer',
            'location' => 'Anytown',
            'email' => 'you@example.com',
            'github_url' => 'https://github.com/your-username',
            'linkedin_url' => 'https://www.linkedin.com/in/your-profile/',
            'intro' => 'DevOps engineer building CI/CD pipelines, Kubernetes platforms and automation for Linux and Windows estates, on-premise and in the cloud. Replace this with your own introduction.',
            'ai_summary' => 'Write a few first-person paragraphs for AI assistants: what you do, what you are strongest at, your standout achievements and the roles you want. It is published at /llms.txt.',
            'contact_heading' => 'Hiring for DevOps or platform engineering?',
            'show_mascot' => true,
            'meta_description' => 'Alex Example, DevOps engineer: CI/CD, Kubernetes, cloud, observability and automation.',
        ]);

        Experience::query()->delete();
        $roles = [
            [
                'title' => 'Senior Platform Engineer',
                'organisation' => 'Example Corp',
                'started_on' => '2024-01-01',
                'ended_on' => null,
                'highlights' => [
                    'Work closely with development teams on CI/CD and application deployments, from build pipelines through to production releases.',
                    'Act as an advisor on high availability, helping teams design and run critical services that stay up.',
                    'Run container platforms and observability for a fleet of Linux and Windows servers.',
                ],
            ],
            [
                'title' => 'DevOps Engineer',
                'organisation' => 'Sample Ltd',
                'started_on' => '2021-03-01',
                'ended_on' => '2024-01-01',
                'highlights' => [
                    'Built CI/CD pipelines in GitHub Actions and Jenkins.',
                    'Automated server builds and patching with Ansible.',
                ],
            ],
            [
                'title' => 'IT Support Analyst',
                'organisation' => 'Placeholder Inc',
                'started_on' => '2019-06-01',
                'ended_on' => '2021-03-01',
                'highlights' => [],
            ],
        ];
        foreach ($roles as $role) {
            Experience::create(['highlights' => implode("\n", $role['highlights'])] + $role);
        }

        Education::query()->delete();
        $education = [
            ['qualification' => 'BSc (Hons) Computer Science', 'institution' => 'Example University', 'period' => '2015 – 2019', 'grade' => 'First Class Honours', 'featured' => true],
            ['qualification' => 'MSc Cyber Security', 'institution' => 'Example University', 'period' => '2024 – 2026', 'grade' => 'In progress', 'in_progress' => true],
            ['qualification' => 'Red Hat Certified System Administrator (RHCSA)', 'institution' => 'Red Hat', 'grade' => 'Certification'],
        ];
        foreach ($education as $i => $row) {
            Education::create($row + ['sort_order' => $i]);
        }

        SkillGroup::query()->delete();
        $skills = [
            'Cloud & platforms' => 'Azure Functions, Azure App Service, Kubernetes, Docker, Helm, ArgoCD, Red Hat Linux, Windows Server, VMware, Proxmox, AD / LDAP',
            'CI/CD & infrastructure as code' => 'GitHub Actions, GitLab CI, Jenkins, Bitbucket Pipelines, Ansible, AWX, Git, Bash, PowerShell, REST APIs',
            'Observability' => 'OpenTelemetry, Grafana, Prometheus, Loki, Tempo, Mimir, Alloy, ELK',
            'Security' => 'SIEM, vulnerability management, threat intelligence, MFA, Trivy, GDPR',
            'Automation & low-code' => 'Power Automate, Power Platform solutions, Microsoft Lists, Python, PowerShell',
            'Languages' => 'Python, PHP, JavaScript, Java, Rust, Swift',
        ];
        $i = 0;
        foreach ($skills as $name => $list) {
            SkillGroup::create(['name' => $name, 'skills' => $list, 'sort_order' => $i++]);
        }
    }
}

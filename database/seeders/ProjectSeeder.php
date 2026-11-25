<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Homelab Kubernetes cluster',
                'date' => '2026-09-01',
                'category' => 'Platform',
                'tags' => 'Kubernetes, Proxmox, ArgoCD, Helm',
                'summary' => 'A three-node bare-metal cluster at home, provisioned on Proxmox and managed entirely through GitOps with ArgoCD.',
                'description' => "Every app on the cluster is a Helm chart in a Git repository. ArgoCD keeps the cluster in sync with the repo, so rebuilding a node is a reinstall and a sync.\n\nUsed as a test bed for upgrades and new tooling before they go anywhere near production.",
                'link' => 'https://github.com/your-username',
            ],
            [
                'title' => 'Self-hosted observability stack',
                'date' => '2026-06-01',
                'category' => 'Observability',
                'tags' => 'Grafana, Prometheus, Loki, OpenTelemetry',
                'summary' => 'Metrics, logs and traces for every machine and service at home, with alerts sent to my phone.',
                'description' => "Prometheus scrapes metrics, Loki collects logs and OpenTelemetry carries traces, all visualised in Grafana.\n\nAlert rules are kept in Git alongside the dashboards, so the whole stack can be rebuilt from scratch.",
            ],
            [
                'title' => 'Ansible workstation bootstrap',
                'date' => '2026-02-01',
                'category' => 'Automation',
                'tags' => 'Ansible, Bash, Linux',
                'summary' => 'One command takes a fresh Linux install to my fully configured workstation: packages, dotfiles, security tools and services.',
                'description' => "Roles are split by concern (base system, developer tools, security hardening, desktop) so each can be applied on its own.\n\nRuns idempotently, so it doubles as a way to keep machines in line over time.",
                'link' => 'https://github.com/your-username',
            ],
        ];

        foreach ($projects as $project) {
            Portfolio::updateOrCreate(['title' => $project['title']], $project);
        }
    }
}

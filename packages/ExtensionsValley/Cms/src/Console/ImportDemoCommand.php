<?php

namespace ExtensionsValley\Cms\Console;

use ExtensionsValley\Cms\Database\seeds\ThemeDemoSeeder;
use Illuminate\Console\Command;

class ImportDemoCommand extends Command
{
    protected $signature = 'laflux:import-demo';

    protected $description = 'One-click import of multilingual demo content (Languages, Hero Slider, Pages, Posts, Categories, Tags, Menus, and Contact Form)';

    public function handle(): int
    {
        $this->info('Importing LaFlux CMS multilingual demo content...');

        $seeder = new ThemeDemoSeeder();
        $seeder->run();

        $this->info('Demo content imported successfully!');
        $this->line('- Languages: English (en, LTR) & Arabic (ar, RTL)');
        $this->line('- Hero Slider: [banner slug="home-hero"] (3 slides)');
        $this->line('- Pages: About Us, Services, Contact Us (EN & AR)');
        $this->line('- Posts: 6 sample articles with categories & tags (EN & AR)');
        $this->line('- Menus: Header & Footer navigation (EN & AR)');
        $this->line('- Contact Form: [form slug="contact-us"]');

        return self::SUCCESS;
    }
}


<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GeneratePWAIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:generate-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PWA icons from the existing logo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating PWA icons...');

        // Define icon sizes needed for PWA
        $iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];

        // Create ImageManager instance with GD driver
        $manager = new ImageManager(new Driver());

        // Create a simple colored square as our icon
        $sourceImage = $manager->create(512, 512);
        
        // Fill with a gradient similar to the SVG
        $sourceImage->fill(function ($x, $y) {
            // Create a gradient from #667eea to #764ba2
            $r1 = 102; $g1 = 126; $b1 = 234; // #667eea
            $r2 = 118; $g2 = 75; $b2 = 162;  // #764ba2
            
            // Calculate gradient position (0 to 1)
            $pos = sqrt(pow($x / 512, 2) + pow($y / 512, 2));
            $pos = min(1, $pos * 1.4); // Adjust for diagonal gradient
            
            // Interpolate colors
            $r = intval($r1 + ($r2 - $r1) * $pos);
            $g = intval($g1 + ($g2 - $g1) * $pos);
            $b = intval($b1 + ($b2 - $b1) * $pos);
            
            return [$r, $g, $b];
        });

        // Create icons directory if it doesn't exist
        $iconsDir = public_path('icons');
        if (!File::exists($iconsDir)) {
            File::makeDirectory($iconsDir, 0755, true);
        }

        // Generate PNG icons for each size
        foreach ($iconSizes as $size) {
            $outputFile = "$iconsDir/icon-{$size}x{$size}.png";
            $this->info("Generating $outputFile...");

            try {
                // Clone the source image and resize
                $image = $sourceImage->clone();
                $image->scale($size, $size);

                // Save as PNG
                $image->toPng()->save($outputFile);

                if (File::exists($outputFile)) {
                    $this->info("  ✓ Created $outputFile");
                } else {
                    $this->error("  ✗ Failed to create $outputFile");
                }
            } catch (\Exception $e) {
                $this->error("  ✗ Error creating $outputFile: " . $e->getMessage());
            }
        }

        $this->info('PWA icon generation complete!');
        $this->info('Make sure to update your manifest.json to reference these icons.');

        return 0;
    }
}
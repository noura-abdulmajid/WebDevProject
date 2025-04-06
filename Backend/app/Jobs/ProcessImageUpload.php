<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class ProcessImageUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing image upload in queue', ['file_path' => $this->filePath]);

            // Create image manager instance
            $manager = new ImageManager(new Driver());
            
            // Read image from storage
            $image = $manager->read(storage_path('app/public/' . $this->filePath));
            
            // Resize image
            $image->scale(width: 800);
            
            // Save the resized image
            $image->save(storage_path('app/public/' . $this->filePath));

            Log::info('Image processed successfully', ['file_path' => $this->filePath]);
        } catch (\Exception $e) {
            Log::error('Failed to process image in queue: ' . $e->getMessage(), [
                'file_path' => $this->filePath,
                'error' => $e->getMessage()
            ]);
            
            // Delete the uploaded file if processing fails
            Storage::disk('public')->delete($this->filePath);
        }
    }
} 
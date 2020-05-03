<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     * We have defined the URL https://atomic.incfile.com/fakepost as default but you can add another one
     * @var string
     */
    protected $signature = 'post:verifier {url=https://atomic.incfile.com/fakepost}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the url if it is available';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $url = $this->argument('url');
        $client = new \GuzzleHttp\Client();
        // Send an asynchronous request to handle a lot of requests.
        $request = new \GuzzleHttp\Psr7\Request('POST', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            $this->info("The url it is avalable");
            return $response;
        }, function ($exception) {
            $this->error("The url it is not avalable");
            return $exception->getMessage();
        });
        try {
            // Here we are handling the url if it is a success response
            $response = $promise->wait();
            $data = json_decode($response->getBody());
            $this->info('Printing the response');
            dd($data);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}

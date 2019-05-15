<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use JasperPHP\JasperPHP;
use App\Http\Controllers\Controller;
use App\Customer;


class ReportController extends Controller
{
    /**
     * Reporna um array com os parametros de conexão
     * @return Array
     */

    public function index()
    {
        // coloca na variavel o caminho do novo relatório que será gerado
        //$input = base_path('/reports/Modelo.jrxml');
        $inputJRMXL = public_path('/reports/Modelo.jrxml');
        $input = public_path('reports/modelo.jasper');   
        $output = public_path('reports/' . time() . '_monitorado');
        $jdbc_dir = base_path('/vendor/geekcom/phpjasper-laravel/bin/jasperstarter/jdbc');
        $options = ['pdf', 'rtf'];
        $conection = [
                'driver' => env('DB_CONNECTION'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'jdbc_driver' => 'org.mariadb.jdbc.Driver',
                'jdbc_url' => 'jdbc:mariadb://127.0.0.1:3306;databaseName=agendamento_db',
                'jdbc_dir' => $jdbc_dir
        ];

        
        try {
            $jasper = new JasperPHP;

            //Transforma o .jrxml em .jasper
            $jasper->compile($inputJRMXL)->execute();
            
            //Transforma o .jasper em .pdf ou .rtf
            $jasper->process(
                $input,
                $output,
                $options,
                [],
                $conection
            )->execute();
    
            $file = $output . '.pdf';
            $path = $file;
        } catch (Exception $e) {
            console.log('Erro: ' . $e );
        }
        //dd($path);
            
        // caso o arquivo não tenha sido gerado retorno um erro 404
        if (!file_exists($file)) {
            abort(404);
        }

        //caso tenha sido gerado pego o conteudo
        $file = file_get_contents($file);
        
        //deleto o arquivo gerado, pois iremos mandar o conteudo para o navegador
        unlink($path);

        // retornamos o conteudo para o navegador que íra abrir o PDF
        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="monitorado.pdf"');
       


    }

/*
 $report = new JasperPHP;
        $report->compile(base_path('/vendor/cossou/jasperphp/examples/hello_world.jrxml'))->execute();



        $report->process(
            base_path('/vendor/cossou/jasperphp/examples/hello_world.jasper'),
            false,
            array('pdf', 'rtf'),
            array('php_version' => phpversion())
        )->execute();


        [
            'driver' => 'org.mariadb.jdbc.Driver',
            'host' => env('DB_HOST', '172.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'agendamento_db'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'jdbc_driver' => 'org.mariadb.jdbc.Driver',
            'jdbc_url' => 'jdbc:mariadb://'.env('DB_HOST', '172.0.0.1').':'.env('DB_PORT', '3306').';databaseName='.env('DB_DATABASE', 'agendamento_db'),
            'jdbc_dir' => '/vendor/geekcom/src/JasperStarter/jdbc/'

        ]

        --------------------------
        $report->process(
            $input,
            $output,
            array('pdf', 'rtf'),
            array(),
            array('php_version' => phpversion()),
            [
                'driver'   => env('DB_CONNECTION'),
                'host'     => env('DB_HOST'),
                'port'     => env('DB_PORT'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'database' => env('DB_DATABASE')
            ]
        )->execute();

        /*
        $report->process(
            $input,
            $output,
            array("pdf"),
            array(),
            $this->getDatabaseConfig()
        )->output();

        -------------------
        public function getDatabaseConfig()
    {
        return [
            'driver'   => env('DB_CONNECTION'),
            'host'     => env('DB_HOST'),
            'port'     => env('DB_PORT'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'jdbc_dir' => base_path() . env('JDBC_DIR', '/vendor/lavela/phpjasper/src/JasperStarter/jdbc'),
        ];
    }
    
    
    
    */
}
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

    
    /*
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
    }*/

    public function index()
    {
        /*
        $report = new JasperPHP;
        $report->compile(base_path('/vendor/cossou/jasperphp/examples/hello_world.jrxml'))->execute();



        $report->process(
            base_path('/vendor/cossou/jasperphp/examples/hello_world.jasper'),
            false,
            array('pdf', 'rtf'),
            array('php_version' => phpversion())
        )->execute();
        */
        
        // coloca na variavel o caminho do novo relatório que será gerado
        $input = base_path('/reports/Modelo.jrxml');
        $output = base_path('/reports/' . time() . '_monitorado');

        // instancia um novo objeto JasperPHP
        $report = new JasperPHP;

        // chama o método que irá gerar o relatório
        // passamos por parametro:
        // o arquivo do relatório com seu caminho completo
        // o nome do arquivo que será gerado
        // o tipo de saída
        // parametros ( nesse caso não tem nenhum)   
        
        $report->process(
            $input,
            $output,
            array('pdf', 'rtf'),
            array(),
            array('php_version' => phpversion()),
            array(
                'driver' => 'org.mariadb.jdbc.Driver',
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'agendamento_db',
                'port' => '3306',
              )
        )->execute();

        /*
        $report->process(
            $input,
            $output,
            array("pdf"),
            array(),
            $this->getDatabaseConfig()
        )->output();
        */
        $file = $output . '.pdf';
        $path = $file;
        //dd($file);
            
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
}
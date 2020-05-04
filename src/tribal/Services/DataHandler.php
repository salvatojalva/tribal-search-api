<?php 
namespace Tribal\Services;

use Tribal\Interfaces\DataHandlerInterface;

use \GuzzleHttp\Client;
use \Meng\AsyncSoap\Guzzle\Factory;

class DataHandler implements DataHandlerInterface
{

    private $search_str;

    public function get($search_str)
    {
        $this->search_str = $search_str;

        return array_merge($this->appleSearch(), $this->tvMaze(), $this->crCind());

    }

    private function appleSearch()
    {
        $data = $this->getRest('https://itunes.apple.com/search?term=')["results"];

        return array_map(
            function($val) {   
                $val['src'] = 'apple';
                $val['collectionName'] =  !isset($val['collectionName']) ? $val['trackCensoredName']: $val['collectionName'];
                return $val; 
            },
            $data
        );
    }

    private function tvMaze()
    {
        $data = $this->getRest('http://api.tvmaze.com/search/shows?q=');
        return array_map(
            function($val) {   
                $val['src'] = 'tv_maze';
                $val['kind'] = 'tv';
                $val['collectionName'] = $val['show']['name'];
                return $val; 
            },
            $data
        );
    
    }

    private function getRest($providerULR)
    {
        $client = new Client();
        $response = $client->request('GET', $providerULR.$this->search_str);
        return json_decode($response->getBody(), true);
    }

    private function crCind()
    {

        $params = array('name' => $this->search_str);

        $factory = new Factory();
        $client = $factory->create(new Client(), 'http://www.crcind.com/csp/samples/SOAP.Demo.CLS?WSDL=1');

        $result = $client->call('GetByName', [['name' => $this->search_str ]]);
        $tempRes = json_decode(json_encode($result->GetByNameResult->any), true);
        $new = simplexml_load_string($tempRes); 
        $con = json_encode($new); 
        $newArr = json_decode($con, true);
        $data = isset($newArr["ListByName"]["SQL"]) ? $newArr["ListByName"]["SQL"] : array();

        return array_map(
            function($val) {   
                $val['src'] = 'cr_cind';
                $val['kind'] = 'people';
                $val['collectionName'] = $val['Name'];
                return $val; 
            },
            $data
        );

    }

}
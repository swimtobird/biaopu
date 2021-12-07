<?php
/**
 *
 * User: swimtobird
 * Date: 2021-11-22
 * Email: <swimtobird@gmail.com>
 */

namespace Swimtobird\BiaoPu;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class Gateway
{
    /**
     * @var bool
     */
    protected $is_dev;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(array $config,$is_dev=true)
    {
        $this->setEnv($is_dev);

        $this->config = $config;

        $this->client = new Client();

    }

    /**
     * @param bool $is_dev
     */
    public function setEnv(bool $is_dev)
    {
        $this->is_dev = $is_dev;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->is_dev){
            return 'http://csp-test.biaopu.cloud/api/zk/v1';
        }else{
            return 'https://csp.biaopucloud.com/api/zk/v1';
        }
    }

    /**
     * @param array $data
     * @param $timestamp
     * @return string
     */
    protected function getSign(array $data,$timestamp)
    {
        $data = json_encode($data,320);

        $sign_data = "secretKey={$this->config['secretKey']}&timestamp={$timestamp}&reqData={$data}";

        return strtoupper(md5($sign_data));
    }

    /**
     * @return float
     */
    protected function getTimestamp()
    {
        return Carbon::now()->getPreciseTimestamp(3);
    }

    /**
     * @param array $data
     * @param string $service_name
     * @return array
     */
    protected function prepareData(array $data,string $service_name)
    {
        return [
            'merCode' => $this->config['merCode'],
            'serviceName' => $service_name,
            'timestamp' => $this->getTimestamp(),
            'reqData' => $data
        ];
    }

    /**
     * @param string $service_name
     * @param array $data
     * @return mixed
     */
    public function request(string $service_name,array $data)
    {
        ksort($data);

        $request_data = $this->prepareData($data,$service_name);

        $request_data['sign'] = $this->getSign($data,Arr::get($request_data,'timestamp'));

        /**
         * @var ResponseInterface $response
         */
        $response = $this->client->post($this->getUrl() , [
            'json' => $request_data
        ]);

        $result = json_decode($response->getBody(),true);

        if ($response->getStatusCode()>=500){
            throw new RequestException('BiaoPu Request Error:Server is 500');
        }

        if (isset($result['rspCode']) && '000' === $result['rspCode']){
            throw new RequestException(
                sprintf(
                    'BiaoPu Request Error: %s, %s',
                    $result['error']['code'] ?? '',
                    $result['error']['message'] ?? ''
                )
            );
        }
        return $result;
    }
}
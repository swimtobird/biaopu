<?php
/**
 *
 * User: swimtobird
 * Date: 2021-11-22
 * Email: <swimtobird@gmail.com>
 */

namespace Swimtobird\BiaoPu;


use Carbon\Carbon;
use WebGeeker\Validation\Validation;

class BiaoPu extends Gateway
{
    /**
     * 临登个人注册
     * @param $user_id
     * @param array $data
     * @return mixed
     * @throws \WebGeeker\Validation\ValidationException
     */
    public function register($user_id,array $data)
    {
        Validation::validate($data,[
            'custName' => 'Required',
            'custPhone' => 'Required',
            'custIdCard' => 'Required',
            'idCardFrontUrl' => 'Required',
            'idCardBackUrl' => 'Required',
            'regAgreement' => 'Required',
            'authAgreement' => 'Required',
        ]);

        return $this->request('custRegister',array_merge([
            'merCustId' => $user_id,
            'opItem' => json_encode(['注册时间' => Carbon::now()->format('Y-m-d H:i:s')])
        ],$data));
    }

    /**
     * 临登个人注册查询
     * $user_id
     * @return mixed
     */
    public function getPersonalInfo($user_id)
    {
        return $this->request('queryCustInfo',[
            'merCustId' => $user_id,
        ]);
    }

    /**
     * 临登注销
     * @param $user_id
     * @return mixed
     */
    public function cancelPersonal($user_id)
    {
       return $this->request('custCancel',[
            'merCustId' => $user_id,
        ]);
    }

    /**
     * 任务推送
     * @param $user_id
     * @param array $data
     * @return mixed
     * @throws \WebGeeker\Validation\ValidationException
     */
    public function createJob($user_id,array $data)
    {
        Validation::validate($data,[
            'merTaskNum' => 'Required',
            'taskName' => 'Required',
            'taskBeginTime' => 'Required',
            'taskEndTime' => 'Required',
            'taskRegionP' => 'Required',
            'taskRegionC' => 'Required',
            'taskDesc' => 'Required',
            'taskAmt' => 'Required',
            'taskDemNick' => 'Required',
            'taskDemCustId' => 'Required',
            'taskSerialNo' => 'Required',
            'taskAgreUrl' => 'Required',
            'taskProveUrl' => 'Required',
            'receiptUrl' => 'Required',
            'bankNo' => 'Required',
            'taskCreateTime' => 'Required',
            'taskPushTime' => 'Required',
            'taskOrderTime' => 'Required',
            'taskCompleteTime' => 'Required',
            'taskPayTime' => 'Required',
        ]);

        return $this->request('taskPush',array_merge([
            'merCustId' => $user_id,
            'opItem' => json_encode(['创建时间' => Carbon::now()->format('Y-m-d H:i:s')])
        ],$data));
    }

    /**
     * 任务查询
     * @param $task_num
     * @return mixed
     */
    public function getJob($mer_task_num)
    {
        return $this->request('queryTaskPush',[
            'merTaskNum' => $mer_task_num,
        ]);
    }

    /**
     * 发票申请
     * @param array $data
     * @return mixed
     * @throws \WebGeeker\Validation\ValidationException
     */
    public function applyTax(array $data)
    {
        Validation::validate($data,[
            'merTaxApplyNo' => 'Required',
            'salerTaxName' => 'Required',
            'salerTaxNo' => 'Required',
            'buyerTaxName' => 'Required',
            'buyerTaxType' => 'Required',
            'taxType' => 'Required',
            'taxItem' => 'Required',
            'taxTotalAmt' => 'Required',
            'taxDeliType' => 'Required',
            'recipientName' => 'Required',
            'taskItem' => 'Required',
        ]);

        return $this->request('taxApp',$data);
    }

    /**
     * 发票申请状态查询
     * @param $apply_no
     * @return mixed
     */
    public function getTax($apply_no)
    {
        return $this->request('queryTaxApp',[
            'merTaxApplyNo' => $apply_no,//商户发票申请号
        ]);
    }

    /**
     * 完税证明查询
     * @param $date
     * @return mixed
     */
    public function getTaxProof($date)
    {
        return $this->request('queryTaxProof',[
            'taxProofMonth' => $date,
        ]);
    }
}
<?php
class RazorpayPayment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('is_admin_login') != true) {
            redirect(ADMIN_URL . 'login');
            exit;
        }
        if ($this->session->userdata('role') != 0) {
            redirect(ADMIN_URL . 'dashboard');
        }
        $this->load->model('admin/mdl_webhook_failed');
        $this->load->model('admin/mdl_webhook_authorized');
        $this->load->helper('razorpay_data');
    }

    public function failed()
    {
        $razorPayOrderList = getOrderByRazorPayAllList();
        $data['data'] = array(
            'list' => array(),
            'ordersList' => $razorPayOrderList,
        );
        $data['middle'] = 'admin/razorpayPayment/failedList';
        $this->load->view('admin/template', $data);
    }

    public function success()
    {
        $data['data'] = array('list' => array());
        $data['middle'] = 'admin/razorpayPayment/successList';
        $this->load->view('admin/template', $data);
    }

    function getFailedList()
    {
        $data = $row = array();
        
        $memData = $this->mdl_webhook_failed->getList($_POST);
        $i = $_POST['start'];
        foreach ($memData as $member) {
            $i++;
            /* $onClickSend = "paymentLinkSendByFaildPayment($member->user_id,$member->amount)"; */

            $data[] = array(
                'DT_RowId' => $member->web_fail_id,
                $member->web_fail_id,
                date("d/m/Y",strtotime($member->date)),
                $member->mobile,
                $member->transaction_id,
                $member->amount,
                /* '<a target="_blank" href="https://api.whatsapp.com/send/?phone=%2B91'.$member->mobile.'&text=&app_absent=0">'.$member->mobile.'</a>', */
                $member->email,
                /* $member->link,
                ($member->exp_date!="")?date("d/m/Y h:i:s",strtotime($member->exp_date)):"",
                $member->attempts,
                ($member->paymentLinkSendTime!="")?date("d/m/Y h:i:s",strtotime($member->paymentLinkSendTime)):"", */
                ($member->created_at!="")?date("d/m/Y h:i:s",strtotime($member->created_at)):"",
                ($member->updated_at!="")?date("d/m/Y h:i:s",strtotime($member->updated_at)):"",
                /* '<a href="javascript:void(0)" onclick="'.$onClickSend.'"><button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" title="Send Payment Link"><i class="fa fa-paper-plane"></i></button></a>', */
               /* $button, */
            );
            
        }
        $countData = $this->mdl_webhook_failed->countFiltered($_POST);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $countData,
            "recordsFiltered" => $countData,
            "data" => $data,
        );
        echo json_encode($output);
    }

    function getSuccessList()
    {
        $data = $row = array();
        
        $memData = $this->mdl_webhook_authorized->getList($_POST);
        $i = $_POST['start'];

        foreach ($memData as $member) {
            $i++;

            $data[] = array(
                'DT_RowId' => $member->web_auth_id,
                $member->web_auth_id,
                $member->date,
                $member->transaction_id,
                $member->amount,
                '<a target="_blank" href="https://api.whatsapp.com/send/?phone=%2B91'.$member->mobile.'&text=&app_absent=0">'.$member->mobile.'</a>',
                $member->email,
               /* $button, */

            );
            
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->mdl_webhook_authorized->countAll(),
            "recordsFiltered" => $this->mdl_webhook_authorized->countFiltered($_POST),
            "data" => $data,
        );
        echo json_encode($output);
    }
}

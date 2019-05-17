<?php
/**
 * Plugin Name: Ngan Luong
 * Plugin URI: https://www.nganluong.vn/nganluong/homeDeveloper/DeveloperWordPress.html
 * Description: Full integration for Ngan Luong payment gateway for WooCommerce
 * Version: 1.2.2
 * Author: nguyencamhue
 * Author URI: https://www.nganluong.vn
 * License: 
 */

add_action('plugins_loaded', 'woocommerce_NganLuongVN_init', 0);

function woocommerce_NganLuongVN_init(){
  if(!class_exists('WC_Payment_Gateway')) return;

  class WC_NganLuongVN extends WC_Payment_Gateway{

    // URL checkout của nganluong.vn - Checkout URL for Ngan Luong
    private $nganluong_url;

    // Mã merchant site code
    private $merchant_site_code;

    // Mật khẩu bảo mật - Secure password
    private $secure_pass;

    // Debug parameters
    private $debug_params;
    private $debug_md5;

    function __construct(){
      
      $this -> icon = 'https://www.nganluong.vn//css/newhome/img/logos/logo-nganluong.png'; // Icon URL
      $this -> id = 'nganluong';
      $this -> method_title = 'Ngân Lượng';
      $this -> has_fields = false;

      $this -> init_form_fields();
      $this -> init_settings();

      $this -> title = $this -> settings['title'];
      $this -> description = $this -> settings['description'];

      $this -> nganluong_url = $this -> settings['nganluong_url'];
      $this -> merchant_site_code = $this -> settings['merchant_site_code'];
      $this -> merchant_id = $this -> settings['merchant_id'];
      $this -> secure_pass = $this -> settings['secure_pass'];
      $this -> redirect_page_id = $this -> settings['redirect_page_id'];
	  $this->cur_code = $this -> settings['nlcurrency'];
      $this -> debug = $this -> settings['debug'];
      $this -> order_button_text = __( 'Thanh toán Ngân Lượng', 'woocommerce' );

      $this -> msg['message'] = "";
      $this -> msg['class'] = "";

      if ( version_compare( WOOCOMMERCE_VERSION, '2.0.8', '>=' ) ) {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
             } else {
                add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
            } 
    // Add the page after checkout to redirect to Ngan Luong
    //add_action( 'woocommerce_receipt_nganluong', array(&$this, 'receipt_page') );
    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	//add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'save_account_details' ) );
	add_action( 'woocommerce_thankyou_nganluong', array( &$this, 'thankyou_page' ) );
   }
    function init_form_fields(){
        // Admin fields
       $this -> form_fields = array(
                'enabled' => array(
                    'title' => __('Activate', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Activate the payment gateway for Ngan Luong', 'woocommerce'),
                    'default' => 'no'),
                'title' => array(
                    'title' => __('Name:', 'woocommerce'),
                    'type'=> 'text',
                    'description' => __('Name of payment method (as the customer sees it)', 'woocommerce'),
                    'default' => __('NganLuongVN', 'woocommerce')),
                'description' => array(
                    'title' => __('', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Payment gateway description', 'woocommerce'),
                    'default' => __('Click place order and you will be directed to the Ngan Luong website in order to make payment', 'woocommerce')),
                'merchant_id' => array(
                    'title' => __('NganLuong.vn email address', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Enter the Ngan Luong account email address')),
                'redirect_page_id' => array(
                    'title' => __('Return URL'),
                    'type' => 'select',
                    'options' => $this -> get_pages('Hãy chọn...'),
                    'description' => __('Please choose the URL to return to after checking out at NganLuong.vn. Mặc định chọn trang chi tiết giao dịch', 'woocommerce')
                ),
                'nlcurrency' => array(
                    'title' => __('Currency', 'woocommerce'),
                   'type' => 'text',
                   'default' => 'vnd',
                    'description' => __('"vnd" or "usd"', 'woocommerce')
                ),
               'nganluong_url' => array(
                  'title' => __( 'Ngan Luong URL', 'woocommerce'),
                  'type' => 'text',
				  'description' => __('"https://www.nganluong.vn/checkout.php"', 'woocommerce')
                ),
               'merchant_site_code' => array(
                  'title' => __( 'Merchant Site Code', 'woocommerce'),
                  'type' => 'text'
                ),
                'secure_pass' => array(
                  'title' => __( 'Secure Password', 'woocommerce'),
                  'type' => 'password'
                ),
                'debug' => array(
                    'title' => __('Debug', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Debug Ngan Luong plugin', 'woocommerce'),
                    'default' => 'no')
            );
    }

    public function admin_options(){
      echo '<h3>'.__('NganLuongVN Payment Gateway', 'woocommerce').'</h3>';
      echo '<table class="form-table">';
      // Generate the HTML For the settings form.
      $this -> generate_settings_html();
      echo '</table>';
    }

    /**
     *  There are no payment fields for NganLuongVN, but we want to show the description if set.
     **/
    function payment_fields(){
        if($this -> description) echo wpautop(wptexturize(__($this->description, 'woocommerce')));
    }

    /**
     * Process the payment and return the result
     **/
    function process_payment( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );

     // $order_items = $order->get_items();
	  $order_items = $order->get_items();

      $return_url = $this->get_return_url( $order );
	  //$return_url = 'nganluong.vn';
      $receiver = $this->merchant_id;
      $transaction_info = ''; 
      
      $order_description = $order_id;

      $order_quantity = $order->get_item_count();
      //$discount = $order->get_total_discount();
	  $currency = $this->cur_code;
	  $discount = 0;
      $tax = $order->get_cart_tax();
	  //$tax = 0;
      $fee_shipping = $order->get_total_shipping();
	  

     /*  $product_names = '';
      foreach ($order_items as $order_item) {
        $product_names[] = $order_item['name'];
      }
      $order_description = implode(', ', $product_names); */ // this goes into transaction info, which shows up on Ngan Luong as the description of goods

      $price = $order->get_total() - ($tax + $fee_shipping);
	  $buyer_info = $order->billing_first_name." ".$order->billing_last_name.'*|*'. $order->billing_email.'*|*'.$order->billing_phone.'*|*'.$order->billing_address_1.", ".$order->billing_city;
      $checkouturl = $this->buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_id, $price, $currency, $quantity = 1, $tax, $discount, $fee_cal = 0, $fee_shipping, $order_description, $buyer_info);

      return array(
            'result'    => 'success',
            'redirect'  => $checkouturl
          );      
    }

    /**
     * Receipt Page
     **/
    function receipt_page( $order_id ){
        echo '<p>'.__('We have received your order. <br /><b>You should now be automatically redirected to Ngan Luong to make payment', 'woocommerce').'</p>';
        $checkouturl = $this->generate_NganLuongVN_url( $order_id );

        if ($this->debug == 'yes') {
          // Debug just shows the URL
          echo '<code>' . $checkouturl . '</code>';
          // echo '<p>secure pass ' . $this->secure_pass . '</p>';
          // echo '<p>params ' . strval($this->debug_params) . '</p>';
          // echo '<p>md5 ' . strval($this->debug_md5) . '</p>';
        } else {
          // Adds javascript to the post-checkout screen to redirect to Ngan Luong with a fully-constructed URL
          // Note: wp_redirect() fails with Ngan Luong
          echo '<a href="' . $checkouturl . '">' . __('Click here to checkout if you are not redirected in 5 seconds', 'woocommerce') . '</a>';
          echo "<script type='text/javaScript'><!--
          setTimeout(\"location.href = '" . $checkouturl . "';\",1500);
          --></script>";
        }
    }

    function thankyou_page( $order_id ) {
      // Return to site after checking out with Ngan Luong
      // Note this has not been fully-tested
      global $woocommerce;

      $order = new WC_Order( $order_id );

      // This probably could be written better
      if ($_GET['payment_id']) {
        $transaction_info = ''; // urlencode("Order#".$order_id." | ".$_SERVER['SERVER_NAME']);
        $order_code = $order_id;
        $price =$_GET['price'];
        $payment_id = $_GET['payment_id'];
        $payment_type = $_GET['payment_type'];
        $error_text = $_GET['error_text'];
        $secure_code = $_GET['secure_code'];
        
        // This is from the class provided by Ngan Luong
        // All these parameters should match the ones provided above before checkout
        if ( $this->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code) ) {      
          $new_order_status = 'completed';
		  $order->update_status($new_order_status );
		  // Remove cart
          $woocommerce->cart->empty_cart();
          // Empty awaiting payment session
          unset($_SESSION['order_awaiting_payment']);
		  echo '<script type="text/javascript">
			<!--
			window.location = "'.get_page_link( $this -> redirect_page_id).'"
			//-->
			</script>';
		  
          }
        }      
                  
    }

    function generate_NganLuongVN_url( $order_id ){
      // This is from the class provided by Ngan Luong. Not advisable to mess.
      global $woocommerce;
      $order = new WC_Order( $order_id );

      $order_items = $order->get_items();

      $return_url = $this->get_return_url( $order );
	  //$return_url = 'nganluong.vn';
      $receiver = $this->merchant_id;
      $transaction_info = ''; // urlencode("Order#".$order_id." | ".$_SERVER['SERVER_NAME']);
      
      $order_description = $order_id;

      $order_quantity = $order->get_item_count();
      $discount = $order->get_cart_discount();
	  //$discount = 0;
      $tax = $order->get_cart_tax();
	  //$tax = 0;
      $fee_shipping = $order->get_total_shipping();
	  /* echo esc_html($order->billing_email);
	  echo esc_html( $order->billing_phone ) . "<br/>";
	  echo $order->billing_first_name . "<br/>";
	  echo $order->billing_last_name . "<br/>";
	  echo $order->billing_address_1. "<br/>";
      echo $order->billing_address_2. "<br/>";
     echo $order->billing_city. "<br/>";
    echo $order->billing_country. "<br/>";
	  die; */
      $product_names = '';
      foreach ($order_items as $order_item) {
        $product_names[] = $order_item['name'];
      }
      $order_description = implode(', ', $product_names); // this goes into transaction info, which shows up on Ngan Luong as the description of goods

      $price = $order->get_total() - ($tax + $fee_shipping);
	  $buyer_info = $order->billing_first_name." ".$order->billing_last_name.'*|*'. $order->billing_email.'*|*'.$order->billing_phone.'*|*'.$order->billing_address_1.", ".$order->billing_city;
	//echo $buyer_info;
      $checkouturl = $this->buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_id, $price, $currency = 'vnd', $quantity = 1, $tax, $discount, $fee_cal = 0, $fee_shipping, $order_description, $buyer_info);
      // $checkouturl = $this->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_id, $price);

      return $checkouturl;
    }

    function showMessage($content){
            return '<div class="box '.$this -> msg['class'].'-box">'.$this -> msg['message'].'</div>'.$content;
        }
     // get all pages
    function get_pages($title = false, $indent = true) {
        $wp_pages = get_pages('sort_column=menu_order');
        $page_list = array();
        if ($title) $page_list[] = $title;
        foreach ($wp_pages as $page) {
            $prefix = '';
            // show indented child pages?
            if ($indent) {
                $has_parent = $page->post_parent;
                while($has_parent) {
                    $prefix .=  ' - ';
                    $next_page = get_page($has_parent);
                    $has_parent = $next_page->post_parent;
                }
            }
            // add to page list array array
            $page_list[$page->ID] = $prefix . $page->post_title;
        }
        return $page_list;
    }

  public function buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price)
  {
    // This is from the class provided by Ngan Luong. Not advisable to mess.
    // This one is for simple checkout
    // Mảng các tham số chuyển tới nganluong.vn
    $arr_param = array(
      'merchant_site_code'  =>  strval($this->merchant_site_code),
      'return_url'          =>  strtolower(urlencode($return_url)),
      'receiver'            =>  strval($receiver),
      'transaction_info'    =>  strval($transaction_info),
      'order_code'          =>  strval($order_code),
      'price'               =>  strval($price)          
    );
    $secure_code ='';
    $secure_code = implode(' ', $arr_param) . ' ' . $this->secure_pass;
    $this->debug_params = $secure_code;
    $arr_param['secure_code'] = md5($secure_code);
    $this->debug_md5 = $arr_param['secure_code'];
    
    /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
    $redirect_url = $this->nganluong_url;
    if (strpos($redirect_url, '?') === false)
    {
      $redirect_url .= '?';
    }
    else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
    {
      // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
      $redirect_url .= '&';     
    }
        
    /* Bước 3. tạo url*/
    $url = '';
    foreach ($arr_param as $key=>$value)
    {
      if ($url == '')
        $url .= $key . '=' . $value;
      else
        $url .= '&' . $key . '=' . $value;
    }
    
    return $redirect_url.$url;
  }

  public function buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_code, $price, $currency = 'vnd', $quantity = 1, $tax = 0, $discount = 0, $fee_cal = 0, $fee_shipping = 0, $order_description = '', $buyer_info = '', $affiliate_code = '')
  { 
    // This is from the class provided by Ngan Luong. Not advisable to mess.
    //  This one is for advanced checkout, including taxes and discounts
    if ($affiliate_code == "") $affiliate_code = $this->affiliate_code;
    $arr_param = array(
      'merchant_site_code'  =>  strval($this->merchant_site_code),
      'return_url'          =>  strval(strtolower($return_url)),
      'receiver'            =>  strval($receiver),
      'transaction_info'    =>  strval($transaction_info),
      'order_code'          =>  strval($order_code),
      'price'               =>  strval($price),
      'currency'            =>  strval($currency),
      'quantity'            =>  strval($quantity),
      'tax'                 =>  strval($tax),
      'discount'            =>  strval($discount),
      'fee_cal'             =>  strval($fee_cal),
      'fee_shipping'        =>  strval($fee_shipping),
      'order_description'   =>  strval($order_description),
      'buyer_info'          =>  strval($buyer_info),
      'affiliate_code'      =>  strval($affiliate_code)
    );
    $secure_code ='';
    $secure_code = implode(' ', $arr_param) . ' ' . $this->secure_pass;
    $arr_param['secure_code'] = md5($secure_code);
    /* */
    $redirect_url = $this->nganluong_url;
    if (strpos($redirect_url, '?') === false) {
      $redirect_url .= '?';
    } else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false) {
      $redirect_url .= '&';     
    }
        
    /* */
    $url = '';
    foreach ($arr_param as $key=>$value) {
      $value = urlencode($value);
      if ($url == '') {
        $url .= $key . '=' . $value;
      } else {
        $url .= '&' . $key . '=' . $value;
      }
    }
    
    return $redirect_url.$url;
  }
  
  /*Hàm thực hiện xác minh tính đúng đắn của các tham số trả về từ nganluong.vn*/
  
  public function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
  {
    // This is from the class provided by Ngan Luong. Not advisable to mess.
    // Checks the returned URL from Ngan Luong to see if it matches
    // Tạo mã xác thực từ chủ web
    $str = '';
    $str .= ' ' . strval($transaction_info);
    $str .= ' ' . strval($order_code);
    $str .= ' ' . strval($price);
    $str .= ' ' . strval($payment_id);
    $str .= ' ' . strval($payment_type);
    $str .= ' ' . strval($error_text);
    $str .= ' ' . strval($this->merchant_site_code);
    $str .= ' ' . strval($this->secure_pass);

         // Mã hóa các tham số
    $verify_secure_code = '';
    $verify_secure_code = md5($str);
    
    // Xác thực mã của chủ web với mã trả về từ nganluong.vn
    if ($verify_secure_code === $secure_code) return true;
    
    return false;
  }

}

  function woocommerce_add_NganLuongVN_gateway($methods) {
      $methods[] = 'WC_NganLuongVN';
      return $methods;
  }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_NganLuongVN_gateway' );
}


<?php
namespace LianBian\Client;


use LianBian\Constant\ContentType;
use LianBian\Constant\HttpHeader;
use LianBian\Constant\HttpMethod;
use LianBian\Constant\SystemHeader;
use LianBian\Http\HttpClient;
use LianBian\Http\HttpRequest;

class AliClient {
    private static $appKey = "appKey";
    private static $appSecret = "appSecret";
	//协议(http或https)://域名:端口，注意必须有http://或https://

    public function __construct()
    {
    }

    /**
	*method=GET请求示例
	*/
    /**
     * @param string $host
     * @param string $path
     * @param array $headers
     * @param array $querys
     * @param bool $debug
     * @return mixed
     */
    public function doGet($host, $path, array $headers, array $querys, $debug=false) {
		//域名后、query前的部分
		$request = new HttpRequest($host, $path, HttpMethod::GET, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
		$debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        //指定参与签名的header
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if (is_array($headers)){
            foreach ($headers as $key=>$node){
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ((is_array($querys))){
            foreach ($querys as $key=>$node){
                $request->setQuery($key, $node);
            }
        }

		$response = HttpClient::execute($request);
		return $response;
	}

	/**
	*method=POST且是表单提交，请求示例
	*/
	public function doPostForm($host, $path, $headers, $querys, $bodys, $debug=false) {
		//域名后、query前的部分
		$request = new HttpRequest($host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_FORM);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
		$debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        //同时指定参与签名的header
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if (is_array($headers)){
            foreach ($headers as $key=>$node){
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ((is_array($querys))){
            foreach ($querys as $key=>$node){
                $request->setQuery($key, $node);
            }
        }

		//注意：业务body部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ((is_array($bodys))){
            foreach ($bodys as $key=>$node){
                $request->setBody($key, $node);
            }
        }

		$response = HttpClient::execute($request);
		return $response;
	}

    /**
     * method=POST且是非表单提交，请求示例
     * @param string $host
     * @param string $path
     * @param array $headers
     * @param array $querys
     * @param string $bodyContent
     * @param bool $debug
     * @return mixed
     */
	public function doPostString($host, $path, array $headers, array $querys, $bodyContent, $debug=false) {
		//域名后、query前的部分
		$request = new HttpRequest($host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
		//传入内容是json格式的字符串
		// $bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string(此行)\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\"side\":\"face(#此行此行)\"}\"}}]}";

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
		$debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if (is_array($headers)){
            foreach ($headers as $key=>$node){
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ((is_array($querys))){
            foreach ($querys as $key=>$node){
                $request->setQuery($key, $node);
            }
        }

		//注意：业务body部分，不能设置key值，只能有value
		if (strlen($bodyContent) > 0) {
			$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
			$request->setBodyString($bodyContent);
		}


		$response = HttpClient::execute($request);
		return $response;
	}


    /**
     * method=POST且是非表单提交，请求示例
     * @param string $host
     * @param string $path
     * @param array $headers
     * @param array $querys
     * @param array $bytes
     * @param string $bodyContent
     * @param bool $debug
     * @return mixed
     */
	public function doPostStream($host, $path, array $headers, array $querys, array $bytes, $bodyContent, $debug=false) {
		//域名后、query前的部分
		// $path = "/poststream";
        $request = new HttpRequest($host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
		//Stream的内容
		// $bytes = array();
		//传入内容是json格式的字符串
		// $bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string(此行)\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\"side\":\"face(#此行此行)\"}\"}}]}";

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_STREAM);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
		//mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if (is_array($headers)){
            foreach ($headers as $key=>$node){
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ((is_array($querys))){
            foreach ($querys as $key=>$node){
                $request->setQuery($key, $node);
            }
        }

		//注意：业务body部分，不能设置key值，只能有value
        if (is_array($bytes)){
            foreach($bytes as $byte) {
                $bodyContent .= chr($byte);
            }
        }

		if (0 < strlen($bodyContent)) {
			$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
			$request->setBodyStream($bodyContent);
		}

		$response = HttpClient::execute($request);
		return $response;
	}
}
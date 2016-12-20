<?php

namespace App\Helper;

class OSSFile{
	/**
	 * 图片上传oss
	 * @param $module	参数只能为property或user或comment
	 * @param $type	当module参数为user或comment时type可传参数1:yun,2:b端,3:c端,当module参数为property时type可传参数1:效果图,2:效果图,3:配套图,4:实景图,5:交通图,6:样板图,7:户型图,8:领路费
	 * @param $image
	 */
	public function oss_image_upload( $module, $type, $image ,$url)
	{
		$result				= $this->oss_api_request($url,array(
			'module'	=> $module,
			'type'		=> $type,
			'files'		=> "@{$image['tmp_name']};filename={$image['name']};type={$image['type']}",
		));
		if($result['status'] == TRUE && !isset( $result['decoded_response']['data']['imgUrl'] ))
		{
			$result['status']	= FALSE;
			$result['message']	= '上传失败.接口未返回上传后图片路径.';
		}
		return $result;
	}

	/**
	 * 获取图片url
	 */
	public function oss_get_image_url( $path, $module, $imgMM ,$url)
	{
		$result				= $this->oss_api_request($url,array(
			'module'	=> $module,
			'imgMM'		=> $imgMM,
			'url'		=> $path,
		));
		if($result['status'] == TRUE && !isset( $result['decoded_response']['data']['imgUrl'] ))
		{
			$result['status']	= FALSE;
			$result['message']	= '读取失败.接口未返回图片url.';
		}
		return $result;
	}

	/**
	　* 删除图片，支持多个一起删除，用逗号分割
	 */
	public function oss_del_image_url( $module, $image )
	{
		return $this->oss_api_request('OSS_API_GET_IMAGE_URI',array(
			'module'	=> $module,
			'urls'		=> $image,
		));
	}

	/**
	 * 请求oss api 接口
	 * @param $url
	 */
	public function oss_api_request( $url, $data )
	{
		$result	= array(
			'status'			=> FALSE,			//　上传失败
			'message'			=> '未知错误.',		// 描述信息
			'request'			=> $data,			//　请求参数
			'curl_info'			=> array(),			// curl 信息
			'decoded_response'	=> array(),			// 解析后的返回信息
			'response'			=> '',				//　api接口端返回信息
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response			= curl_exec($ch);
		$curl_errorno		= curl_errno($ch);
		$curl_info			= curl_getinfo($ch);
		$curl_http_code		= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_errormsg		= curl_error($ch);
		curl_close($ch);

		$result['curl_info']		= $curl_info;
		$result['response']			= $response;
		$decoded_response			= json_decode( $response, TRUE );
		$result['decoded_response']	= $decoded_response;

		if(!isset( $decoded_response['code'] ) || !isset( $decoded_response['msg'] ))
		{
			$result['status']		= FALSE;
			$result['message']		= in_array($curl_http_code, array(200, 201, 204, 206)) ? 'API接口返回数据格式异常.' : "CURL ERROR:[http code:{$curl_http_code}][error no:{$curl_errorno}][error msg:{$curl_errormsg}]";
		}

		if( $decoded_response['code'] != '1000' )
		{
			$result['status']		= FALSE;
			$result['message']		= empty( $decoded_response['msg'] ) ? "请求失败" : $decoded_response['msg'] ;
		}

		if( $decoded_response['code'] == '1000')
		{
			$result['status']		= TRUE;
			$result['message']		= empty( $decoded_response['msg'] ) ? "请求成功" : $decoded_response['msg'] ;
		}

		return $result;
	}
}
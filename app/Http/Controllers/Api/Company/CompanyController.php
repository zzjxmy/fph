<?php
/**
 * CopyRight © 2016
 * @desc 公司信息编辑
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     CompanyController.php（类名/函数名）
 * @date:     2016-12-22
 * @directory: /app/classes/Controller/fph/CompanyController.php
 */

namespace App\Http\Controllers\Api\Company;


use App\Http\Controllers\Controller;
use App\Model\Dao\CompanyDao;
use App\Model\Dao\UserDao;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller {
    
    public function handle(){
        if(!is_login())return $this->respondWithData(10000);
        //get company id
        $userCompany = UserDao::find(decrypt($this->request->input('uid')));
        if(!$userCompany || $userCompany->status == 2) return $this->respondWithData(20003);
        //get company info if not empty
        $company = $userCompany->company_id?CompanyDao::find($userCompany->company_id):new CompanyDao();
        switch ($this->request->input('type')){
            case 'name': //update company name
                $name = $this->request->input('name');
                if(empty($name)) return $this->respondWithData(10002);
                $company->name = $name;
                break;
            case 'logo':
                $logo = $this->request->input('logo');
                if(empty($logo)) return $this->respondWithData(10002);
                $company->logo = $logo;
                break;
            case 'genre': //type
                $genre = $this->request->input('genre');
                if(!array_key_exists($genre,config('message.type'))) return $this->respondWithData(10002);
                $company->type = $genre;
                break;
            case 'poc':
                $poc = $this->request->input('poc');
                if(!array_key_exists($poc,config('message.poc'))) return $this->respondWithData(10002);
                $company->poc = $poc;
                break;
            case 'scale':
                $scale = $this->request->input('scale');
                if(!array_key_exists($scale,config('message.scale'))) return $this->respondWithData(10002);
                $company->scale = $scale;
                break;
            case 'intro':
                $intro = $this->request->input('intro');
                if(empty($intro)) return $this->respondWithData(10002);
                $company->intro = $intro;
                break;
            case 'getCompanyInfo':
                return $this->respondWithData(200,[
                    'userInfo' => [
                        'position' => $userCompany->position,
                        'cardimgurl' => $userCompany->cardimgurl,
                    ],
                    'companyInfo' => [
                        'name' => (string)$company->name,
                        'logo' => (string)$company->logo,
                        'type' => (string)$company->type,
                        'poc' => (string)$company->poc,
                        'scale' => (string)$company->scale,
                        'intro' => (string)$company->intro,
                    ],
                    'type'  => config('message.type'),
                    'poc'  => config('message.poc'),
                    'scale'  => config('message.scale'),
                ]);
            default:
                return $this->respondWithData(10002);
        }
        try{
            DB::transaction(function () use ($company,$userCompany) {
                $company->save();
                if(!$userCompany->company_id) $userCompany->update(['company_id'=>$company->id]);
            });
            return $this->respondWithData(200);
        }catch (\Exception $exception){
            return $this->respondWithData(20012);
        }
       
    }
}
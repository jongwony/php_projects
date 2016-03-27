<?php
namespace Festiv\Publ\Repositories;

use Festiv\Publ\Models\User;
use InvalidArgumentException;
use Illuminate\Contracts\Hashing\Hasher;
use Respect\Validation\Validator;
use Wandu\Laravel\Repository\PaginationRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Traits\UsePaginationRepository;

class UserRepository extends Repository implements PaginationRepositoryInterface
{
    use UsePaginationRepository;

    /** @var string */
    protected $model = User::class;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function createItem(array $dataSet)
    {
        foreach (['name', 'email', 'password1', 'password2'] as $key) {
            if (!array_key_exists($key, $dataSet) || $dataSet[$key] === '') {
                $names = [
                    'name' => '이름',
                    'email' => '이메일',
                    'password1' => '비밀번호',
                    'password2' => '비밀번호 확인',
                ];
                throw new InvalidArgumentException("{$names[$key]}(이)가 입력되지 않습니다.");
            }
        }
        return parent::createItem($dataSet);
    }

    public function filterDataSet(array $dataSet)
    {
        if (isset($dataSet['name']) && !Validator::stringType()->length(2, 50)->validate($dataSet['name'])) {
            throw new InvalidArgumentException("이름은 2~50글자 사이입니다.");
        }
        if (isset($dataSet['email']) && !Validator::email()->validate($dataSet['email'])) {
            throw new InvalidArgumentException("이메일의 형식이 아닙니다.");
        }
        if (isset($dataSet['password1'])) {
            if ($dataSet['password1'] !== $dataSet['password2']) {
                throw new InvalidArgumentException("입력된 비밀번호가 서로 다릅니다.");
            }
            if (!Validator::stringType()->length(8)->validate($dataSet['password1'])) {
                throw new InvalidArgumentException("비밀번호는 최소 8글자 이상이어야 합니다.");
            }
            $dataSet['password'] = $dataSet['password1'];
            unset($dataSet['password1'], $dataSet['password2']);
        }
        if (isset($dataSet['password'])) {
            $dataSet['password'] = $this->hasher->make($dataSet['password']);
        }
        if (isset($dataSet['grant'])) {
            if (!Validator::intType()->min(0)->max(255)->validate($dataSet['grant'])) {
                throw new InvalidArgumentException("잘못된 권한입니다.");
            }
        }
        return $dataSet;
    }
}

<?php
namespace Festiv\Publ\Repositories;

use Festiv\Publ\Models\Category;
use InvalidArgumentException;
use Respect\Validation\Validator;
use Wandu\Laravel\Repository\Repository;

class CategoryRepository extends Repository
{
    /** @var string */
    protected $model = Category::class;

    /** @var array */
    protected $orders = ['id' => true];

    /**
     * @param array $dataSet
     * @return array
     */
    public function filterDataSet(array $dataSet)
    {
        $dataSet += [
            'description' => '',
        ];
        foreach (['name'] as $key) {
            if (!array_key_exists($key, $dataSet) || $dataSet[$key] === '') {
                $names = [
                    'name' => '이름',
                ];
                throw new InvalidArgumentException("{$names[$key]}(이)가 입력되지 않습니다.");
            }
        }
        if (!Validator::stringType()->length(1)->validate($dataSet['name'])) {
            throw new InvalidArgumentException("이름은 적어도 한글자 이상이어야 합니다.");
        }
        return $dataSet;
    }
}

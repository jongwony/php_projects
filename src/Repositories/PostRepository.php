<?php
namespace Festiv\Publ\Repositories;

use Festiv\Publ\Models\Post;
use InvalidArgumentException;
use Respect\Validation\Validator;
use Wandu\Laravel\Repository\PaginationRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Traits\UsePaginationRepository;

class PostRepository extends Repository implements PaginationRepositoryInterface
{
    use UsePaginationRepository;

    /** @var string */
    protected $model = Post::class;

    /** @var array */
    protected $orders = ['id' => false];

    /**
     * @Autowired
     * @var \Festiv\Publ\Repositories\UserRepository
     */
    protected $users;

    /**
     * @Autowired
     * @var \Festiv\Publ\Repositories\CategoryRepository
     */
    protected $categories;


    protected function createQuery()
    {
        return parent::createQuery()->with('category');
    }

    public function filterDataSet(array $dataSet)
    {
        if (!Validator::stringType()->length(3, 100)->validate($dataSet['title'])) {
            throw new InvalidArgumentException("제목은 3~100글자 사이입니다.");
        }
        if (!Validator::stringType()->length(10)->validate($dataSet['contents'])) {
            throw new InvalidArgumentException("본문은 최소 10글자는 작성하셔야 합니다.");
        }
        if ($dataSet['user_id'] !== 0) {
            if (null === $item = $this->users->getItem($dataSet['user_id'])) {
                throw new InvalidArgumentException("잘못된 사용자 정보입니다.");
            }
            $dataSet['writer'] = $item['name'];
        }
        if ($dataSet['category_id'] !== 0) {
            if (null === $item = $this->categories->getItem($dataSet['category_id'])) {
                throw new InvalidArgumentException("잘못된 카테고리 정보입니다.");
            }
        }

        return $dataSet;
    }
}

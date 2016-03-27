<?php
namespace Festiv\Publ\Http\Controllers\Admin;

use Exception;
use Festiv\Pagination\Builder;
use Festiv\Publ\Repositories\UserRepository;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Wandu\Http\Exception\NotFoundException;
use Wandu\Http\Parameters\ParsedBody;
use Wandu\Http\Parameters\QueryParams;
use function Festiv\Http\Response\back;
use function Festiv\Http\Response\redirect;
use function Festiv\View\render;

class UserController
{
    /** @var \Wandu\Http\Contracts\SessionInterface */
    protected $session;

    /** @var \Festiv\Publ\Repositories\UserRepository */
    protected $users;

    /** @var array */
    protected $grants = [
        0 => '관리자',
        255 => '일반사용자',
    ];

    public function __construct(
        ServerRequestInterface $request,
        UserRepository $users
    ) {
        $this->session = $request->getAttribute('session');
        $this->users = $users;
    }

    public function index(ServerRequestInterface $request)
    {
        $builder = new Builder($request, new QueryParams($request));

        return render('admin/users/index', [
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'items' => $builder->build($this->users),
            'grants' => $this->grants,
        ]);
    }

    public function show(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');

        $item = $this->users->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }
        return render('admin/users/show', [
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'values' => $this->session->get('values', []) + $item->toArray(),
            'grants' => $this->grants,
        ]);
    }

    public function update(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');

        $item = $this->users->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }

        $parsedBody = new ParsedBody($request);
        $dataToUpdate = [
            'name' => $parsedBody->get('name'),
            'email' => $parsedBody->get('email'),
            'grant' => $parsedBody->get('grant', null, ['cast' => 'int']),
        ];
        try {
            if ($this->users->findItems(['grant' => 0])->count() === 1 && $item['grant'] === 0) {
                throw new RuntimeException("마지막 관리자는 권한을 바꿀 수 없습니다.");
            }
            $this->users->updateItem($id, $dataToUpdate);
        } catch (Exception $e) {
            $this->session->flash('values', $dataToUpdate);
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect("/admin/users/{$id}");
    }

    public function delete(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $item = $this->users->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }
        try {
            if ($item['id'] === $this->session->get('user')['id']) {
                throw new RuntimeException("자기 자신은 삭제할 수 없습니다");
            }
            if ($this->users->findItems(['grant' => 0])->count() === 1 && $item['grant'] === 0) {
                throw new RuntimeException("마지막 관리자는 삭제할 수 없습니다.");
            }
            $this->users->deleteItem($id);
        } catch (Exception $e) {
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect('/admin/users');
    }
}

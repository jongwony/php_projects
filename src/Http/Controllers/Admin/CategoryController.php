<?php
namespace Festiv\Publ\Http\Controllers\Admin;

use Exception;
use Festiv\Publ\Repositories\CategoryRepository;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Wandu\Http\Exception\NotFoundException;
use function Festiv\Http\Response\back;
use function Festiv\Http\Response\redirect;
use function Festiv\View\render;

class CategoryController
{
    /** @var \Wandu\Http\Contracts\SessionInterface */
    protected $session;

    /** @var \Festiv\Publ\Repositories\CategoryRepository */
    protected $categories;

    public function __construct(
        ServerRequestInterface $request,
        CategoryRepository $categories
    ) {
        $this->session = $request->getAttribute('session');
        $this->categories = $categories;
    }

    public function index()
    {
        return render('admin/categories/index', [
            'values' => $this->session->get('values', []),
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'categories' => $this->categories->getAllItems(),
        ]);
    }

    public function create(ServerRequestInterface $request, ParameterInterface $params)
    {
        $dataSet = [
            'name' => $params->get('name'),
            'description' => $params->get('description', ''),
        ];
        try {
            $this->categories->createItem($dataSet);
        } catch (Exception $e) {
            $this->session->flash('values', $dataSet);
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect('/admin/categories');
    }

    public function show(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');

        $item = $this->categories->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }
        return render('admin/categories/show', [
            'values' => $this->session->get('values', []) + $item->toArray(),
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'categories' => $this->categories->getAllItems(),
        ]);
    }

    public function update(ServerRequestInterface $request, ParameterInterface $params)
    {
        $id = $request->getAttribute('id');

        $item = $this->categories->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }

        $dataToUpdate = [
            'name' => $params->get('name'),
            'description' => $params->get('description', ''),
            'options' => $params->get('options', []),
        ];
        try {
            $dataToUpdate['options']['tab_enabled'] = (
                isset($dataToUpdate['options']['tab_enabled']) && $dataToUpdate['options']['tab_enabled']
            );
            $dataToUpdate['options']['tabs'] = array_filter(explode(',', $dataToUpdate['options']['tabs']));

            if (!isset($dataToUpdate['options']['permit_write']) || !in_array($dataToUpdate['options']['permit_write'], ['all', 'member', 'admin'])) {
                throw new InvalidArgumentException('권한은 all, member, admin 값만 사용가능합니다.');
            }
            if (!isset($dataToUpdate['options']['permit_read']) || !in_array($dataToUpdate['options']['permit_read'], ['all', 'member', 'admin'])) {
                throw new InvalidArgumentException('권한은 all, member, admin 값만 사용가능합니다.');
            }
            $this->categories->updateItem($id, $dataToUpdate);
        } catch (Exception $e) {
            $this->session->flash('values', $dataToUpdate);
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect("/admin/categories/{$id}");
    }

    public function delete(ServerRequestInterface $request, ParameterInterface $params)
    {
        $id = $request->getAttribute('id');

        $item = $this->categories->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }

        try {
            $this->categories->deleteItem($id);
        } catch (Exception $e) {
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect('/admin/categories');
    }
}

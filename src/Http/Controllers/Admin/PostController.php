<?php
namespace Festiv\Publ\Http\Controllers\Admin;

use Exception;
use Festiv\Pagination\Builder;
use Festiv\Publ\Repositories\CategoryRepository;
use Festiv\Publ\Repositories\PostRepository;
use Psr\Http\Message\ServerRequestInterface;
use Wandu\Http\Exception\NotFoundException;
use Wandu\Http\Parameters\ParsedBody;
use Wandu\Http\Parameters\QueryParams;
use function Festiv\Http\Response\back;
use function Festiv\Http\Response\redirect;
use function Festiv\View\render;

class PostController
{
    /** @var \Wandu\Http\Contracts\SessionInterface */
    protected $session;

    /** @var \Festiv\Publ\Repositories\CategoryRepository */
    protected $categories;

    /** @var \Festiv\Publ\Repositories\PostRepository */
    protected $posts;

    public function __construct(
        ServerRequestInterface $request,
        CategoryRepository $categories,
        PostRepository $posts
    ) {
        $this->session = $request->getAttribute('session');
        $this->categories = $categories;
        $this->posts = $posts;
    }

    public function index(ServerRequestInterface $request)
    {
        $builder = new Builder($request, new QueryParams($request));

        return render('admin/posts/index', [
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'items' => $builder->build($this->posts),
        ]);
    }

    public function write()
    {
        return render('admin/posts/write', [
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'values' => $this->session->get('values', []),
            'categories' => $this->categories->getAllItems(),
        ]);
    }

    public function create(ServerRequestInterface $request)
    {
        $parsedBody = new ParsedBody($request);
        $dataToCreate = [
            'title' => $parsedBody->get('title'),
            'contents' => $parsedBody->get('contents', ''),
            'user_id' => (int) $parsedBody->get('user_id', 0),
            'category_id' => (int) $parsedBody->get('category_id', 0),
         ];
        try {
            $this->posts->createItem($dataToCreate);
        } catch (Exception $e) {
            $this->session->flash('values', $dataToCreate);
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect('/admin/posts');
    }

    public function show(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');

        $item = $this->posts->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }
        return render('admin/posts/write', [
            'errors' => $this->session->get('errors', []),
            'user' => $this->session->get('user', []),
            'values' => $this->session->get('values', []) + $item->toArray(),
            'categories' => $this->categories->getAllItems(),
        ]);
    }

    public function update(ServerRequestInterface $request)
    {
        $parsedBody = new ParsedBody($request);
        $id = $request->getAttribute('id');

        $item = $this->posts->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }

        $dataToUpdate = [
            'title' => $parsedBody->get('title'),
            'contents' => $parsedBody->get('contents', ''),
            'user_id' => (int) $parsedBody->get('user_id', 0),
            'category_id' => (int) $parsedBody->get('category_id', 0),
        ];

        try {
            $this->posts->updateItem($id, $dataToUpdate);
        } catch (Exception $e) {
            $this->session->flash('values', $parsedBody->toArray());
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect("/admin/posts/{$id}");
    }

    public function delete(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');

        $item = $this->posts->getItem($id);
        if (!$item) {
            throw new NotFoundException();
        }
        try {
            $this->posts->deleteItem($id);
        } catch (Exception $e) {
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect('/admin/posts');
    }
}

<?php
namespace Festiv\Publ\Http\Controllers;

use Exception;
use Festiv\Publ\Repositories\UserRepository;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Illuminate\Contracts\Hashing\Hasher;
use InvalidArgumentException;
use PHPMailer;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Wandu\Http\Parameters\ParsedBody;
use Wandu\Http\Parameters\QueryParams;
use function Festiv\Http\Response\back;
use function Festiv\Http\Response\redirect;
use function Festiv\View\render;

class AuthController
{
    /** @var \Wandu\Http\Contracts\SessionInterface */
    protected $session;

    /** @var \Festiv\Publ\Repositories\UserRepository */
    protected $users;

    public function __construct(ServerRequestInterface $request, UserRepository $users) {
        $this->session = $request->getAttribute('session');
        $this->users = $users;
    }

    public function login(ServerRequestInterface $request)
    {
        $queryParams = new QueryParams($request);

        // auto logout
        $this->logouting($request);

        return render('auth/login', [
            'redirect' => $queryParams->get('redirect', '/'),
            'values' => $this->session->get('values', []),
            'errors' => $this->session->get('errors', []),
            'successes' => $this->session->get('successes', []),
        ]);
    }

    public function logining(ServerRequestInterface $request, Hasher $hasher)
    {
        $parsedBody = new ParsedBody($request);

        $email = $parsedBody->get('email');
        $password = $parsedBody->get('password');

        $user = $this->users->findItems(['email' => $email])->first();
        if (!$user || !$hasher->check($password, $user['password'])) {
            $this->session->flash('values', $parsedBody->toArray());
            $this->session->flash('errors', ['로그인 정보가 잘못되었습니다.']);
            return back($request);
        }

        // Login Logic
        $this->session->set('is_login', true);
        $this->session->set('user', $user->toArray());

        return redirect($parsedBody->get('redirect', '/'));
    }

    public function logouting(ServerRequestInterface $request)
    {
        $parsedBody = new ParsedBody($request, new QueryParams($request));

        // Logout Logic
        $this->session->remove('is_login');
        $this->session->remove('user');

        return redirect($parsedBody->get('redirect', '/'));
    }

    public function register()
    {
        return render('auth/register', [
            'values' => $this->session->get('values', []),
            'errors' => $this->session->get('errors', []),
        ]);
    }

    public function registering(ServerRequestInterface $request)
    {
        $parsedBody = new ParsedBody($request);
        $dataSet = [
            'name' => $parsedBody->get('name'),
            'email' => $parsedBody->get('email'),
            'password1' => $parsedBody->get('password1'),
            'password2' => $parsedBody->get('password2'),
        ];
        try {
            $this->users->createItem($dataSet);
        } catch (Exception $e) {
            $this->session->flash('values', $dataSet);
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        return redirect('/');
    }

    public function reset()
    {
        return render('auth/reset', [
            'values' => $this->session->get('values', []),
            'errors' => $this->session->get('errors', []),
        ]);
    }

    public function resetting(ServerRequestInterface $request, PHPMailer $mailer)
    {
        $parsedBody = new ParsedBody($request);
        $dataSet = [
            'email' => $parsedBody->get('email'),
        ];
        try {
            $user = $this->users->findItems($dataSet)->first();
            if (!$user) {
                throw new InvalidArgumentException("해당하는 회원이 존재하지 않습니다.");
            }
            $password = $this->generatePassword();
            $this->users->updateItem($user['id'], [
                'password' => $password,
            ]);
            $mailer->setFrom('publ@publ.com');
            $mailer->addAddress($user['email'], $user['name']);
            $mailer->isHTML(true);
            $mailer->Subject = '비밀번호 찾기 결과입니다.';
            $mailer->Body = render('auth/reset-mail', ['password' => $password]);
            if (!$mailer->send()) {
                throw new RuntimeException("메일 전송에 실패하였습니다.");
            }
        } catch (Exception $e) {
            $this->session->flash('values', $dataSet);
            $this->session->flash('errors', [$e->getMessage()]);
            return back($request);
        }
        $this->session->flash('successes', ['메일을 전송하였습니다.']);
        return redirect('/auth/login');
    }

    protected function generatePassword()
    {
        return (new ComputerPasswordGenerator())
            ->setUppercase()
            ->setLowercase()
            ->setNumbers()
            ->setLength(12)
            ->generatePassword();
    }
}

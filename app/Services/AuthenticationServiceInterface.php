<?php
namespace App\Services;

interface AuthenticationServiceInterface extends BaseServiceInterface
{
    /**
     * @param int $id
     *
     * @return \App\Models\AuthenticationBase
     */
    public function signInById($id);

    /**
     * @param array $input
     *
     * @return \App\Models\AuthenticationBase
     */
    public function signIn($input);

    /**
     * @param array $input
     *
     * @return \App\Models\AuthenticationBase
     */
    public function signUp($input);

    /**
     * @param string $email
     *
     * @return bool
     */
    public function sendPasswordReset($email);

    /**
     * @return bool
     */
    public function signOut();

    /**
     * @return bool
     */
    public function resignation();

    /**
     * @param \App\Models\AuthenticationBase $user
     */
    public function setUser($user);

    /**
     * @return \App\Models\AuthenticationBase
     */
    public function getUser();

    /**
     * @param string $email
     */
    public function sendPasswordResetEmail($email);

    /**
     * @param string $email
     * @param string $password
     * @param string $token
     *
     * @return bool
     */
    public function resetPassword($email, $password, $token);

    /**
     * @return bool
     */
    public function isSignedIn();

    /**
     * @param  $input
     *
     * @return \App\Models\AuthenticationBase
     */
    public function signInByAPI($input);

    /**
     * @param  $input
     *
     * @return \App\Models\AuthenticationBase
     */
    public function signUpByAPI($input);

    /**
     * @param  $input
     *
     * @return \App\Models\AuthenticationBase
     */
    public function registerStoreAccount($input);

    /**
     * @param \App\Models\AuthenticationBase $user
     *
     * @return \App\Models\AuthenticationBase
     */
    public function setAPIAccessToken($user);

    /**
     * @return string
     */
    public function getGuardName();

    /**
     * @param $request
     *
     * @return null
     */
    public function checkClient($request);

    public function resetPasswordApi($email);
}

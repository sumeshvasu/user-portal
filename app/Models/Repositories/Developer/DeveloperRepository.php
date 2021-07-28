<?php
/**
 * Repository : DeveloperRepository.
 *
 * This file used to handling all admin related activities, which all in DeveloperInterface
 *
 * @author Sumesh K V <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */

namespace App\Models\Repositories\Developer;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DeveloperRepository implements DeveloperInterface
{
    // Our Eloquent models
    protected $developerModel;

    /**
     * Setting our class to the injected model.
     *
     * @return DeveloperRepository
     */
    public function __construct(Model $developer)
    {
        $this->developerModel = $developer;
    }

    /**
     * Get admin list.
     *
     * @return array
     */
    public function list()
    {
        return $this->developerModel
            ::orderBy('created_at', 'desc')
            ->paginate(20);
    }

    /**
     * Get admin details.
     *
     * @return array
     */
    public function show($id)
    {
        $user = $this->developerModel
            ::with('roles', 'permissions', 'profile', 'identityCard', 'phone')
            ->findOrFail($id);
        $user->firstname = $user->profile->firstname;
        $user->lastname = $user->profile->lastname;
        $user->phone_code = $user->phone->phone_code ?? null;
        $user->phone = $user->phone->phone ?? null;
        $user->card_number = $user->phone->card_number ?? null;

        return $user;
    }

    /**
     * Store role with admin dashboard permission.
     *
     * @param array $request
     *
     * @return bool
     */
    public function store($request)
    {
        $role = $this->roleModel
            ::with('permissions')
            ->where('id', $request['role_id'])
            ->get();
        $permissions = $role[0]->permissions;
        $adminPermissions = [];

        foreach ($permissions as $key => $value) {
            $adminPermissions[] = $value->name;
        }

        $userInfo['email'] = $request['email'];
        $userInfo['password'] = Hash::make($request['password']);
        $userInfo['status'] = 'A';
        $userInfo['email_verified_at'] = Carbon::now();

        $user = $this->developerModel
            ::create($userInfo)
            ->assignRole($role[0]->name)
            ->givePermissionTo($adminPermissions);

        $profileInfo['user_id'] = $user->id;
        $profileInfo['firstname'] = $request['firstname'];
        $profileInfo['lastname'] = $request['lastname'];
        $profileInfo['avatar'] = $request['avatar'];
        $profileInfo['avatar_type'] = $request['avatar_type'];
        $profileInfo['email_notification'] =
            $request['email_notification'] ?? true;
        $profileInfo['sms_notification'] = $request['sms_notification'] ?? true;

        $this->profileModel::create($profileInfo);

        $phoneInfo['user_id'] = $user->id;
        $phoneInfo['phone_code'] = $request['phone_code'];
        $phoneInfo['phone'] = ltrim($request['phone'], '0');

        $phoneExist = $this->phoneModel
            ::where('phone', $phoneInfo['phone'])
            ->whereNull('deleted_at')
            ->first();
        if ($phoneExist == null) {
            $this->phoneModel::create($phoneInfo);
        }

        return $role[0]->name;
    }

    /**
     * Update admin info.
     *
     * @param array $request
     * @param int   $id
     *
     * @return bool
     */
    public function update($request, $id)
    {
        $user = $this->developerModel
            ::with('profile', 'phone')
            ->findOrFail($id);
        $userFlag = false;

        if ($request['password']) {
            $userFlag = true;
            $user->password = Hash::make($request['password']);
        }

        if ($user->email != $request['email']) {
            $userFlag = true;
            $user->email = $request['email'];
        }

        if ($userFlag) {
            $user->updated_at = Carbon::now();

            $user->update();
        }

        $profile = $this->profileModel->findOrFail($user->profile->id);
        $profileFlag = false;

        if ($profile->firstname != $request['firstname']) {
            $profileFlag = true;
            $profile->firstname = $request['firstname'];
        }

        if ($profile->lastname != $request['lastname']) {
            $profileFlag = true;
            $profile->lastname = $request['lastname'];
        }

        if ($request['avatar'] && $profile->avatar != $request['avatar']) {
            $profileFlag = true;
            $profile->avatar = $request['avatar'];
        }

        if ($profile->email_notification != $request['email_notification']) {
            $profileFlag = true;

            if ($request['email_notification']) {
                $profile->email_notification = true;
            } else {
                $profile->email_notification = false;
            }
        }

        if ($profile->sms_notification != $request['sms_notification']) {
            $profileFlag = true;
            $profile->sms_notification = $request['sms_notification'];

            if ($request['sms_notification']) {
                $profile->sms_notification = true;
            } else {
                $profile->sms_notification = false;
            }
        }

        if ($profile->push_notification != $request['push_notification']) {
            $profileFlag = true;

            if ($request['push_notification']) {
                $profile->push_notification = true;
            } else {
                $profile->push_notification = false;
            }
        }

        if ($profile->web_notification != $request['web_notification']) {
            $profileFlag = true;

            if ($request['web_notification']) {
                $profile->web_notification = true;
            } else {
                $profile->web_notification = false;
            }
        }

        if ($profileFlag) {
            $profile->updated_at = Carbon::now();

            $profile->update();
        }

        $phone = $this->phoneModel->findOrFail($user->phone->id);
        $phoneFlag = false;

        if ($phone->phone_code != $request['phone_code']) {
            $phoneFlag = true;
            $phone->phone_code = $request['phone_code'];
        }

        if ($phone->phone != ltrim($request['phone'], '0')) {
            $phoneFlag = true;
            $phone->phone = ltrim($request['phone'], '0');
        }

        if ($phoneFlag) {
            $phone->updated_at = Carbon::now();

            $phone->update();
        }

        return true;
    }

    /**
     * Update admin status.
     *
     * @param array $request
     * @param int   $id
     *
     * @return bool
     */
    public function updateStatus($request, $id)
    {
        $user = $this->developerModel::findOrFail($id);

        if ($request['status']) {
            $user->status = 'A';
        } else {
            $user->status = 'D';
        }

        return $user->update();
    }

    /**
     * Destroy admin.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id)
    {
        $user = $this->developerModel::findOrFail($id);

        $profile = $this->profileModel
            ::where('user_id', $user->id)
            ->get()
            ->pluck('avatar', 'avatar_type');

        $profile = $profile->toArray();

        if (isset($profile['file'])) {
            if ($profile['file'] && $profile['file'] != 'avatar.svg') {
                if (
                    File::exists(
                        public_path(config('askalan.avatar_path') . $profile[0])
                    )
                ) {
                    File::delete(
                        public_path(config('askalan.avatar_path') . $profile[0])
                    );
                }
            }
        }

        return $user->delete();
    }

    /**
     * Search admins.
     *
     * @param array $request
     *
     * @return object array
     */
    public function search($request, $roles)
    {
        if ($search = $request['q']) {
            $admins = $this->developerModel
                ::with('roles', 'permissions', 'profile')
                ->whereHas('roles', function ($query) use ($roles) {
                    $query->whereIn('name', $roles);
                })
                ->whereHas('profile', function ($query) use ($search) {
                    $query->where('firstname', 'LIKE', "%$search%");
                    $query->orWhere('lastname', 'LIKE', "%$search%");
                })
                ->paginate(
                    config('settings.pagination_count') ??
                        config('askalan.pagination_count')
                );
        } else {
            $admins = $this->developerModel
                ::with('roles', 'permissions', 'profile')
                ->whereHas('roles', function ($query) use ($roles) {
                    $query->whereIn('name', $roles);
                })
                ->paginate(
                    config('settings.pagination_count') ??
                        config('askalan.pagination_count')
                );
        }

        return $admins;
    }
}

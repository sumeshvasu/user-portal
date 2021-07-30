<?php
/**
 * Repository : DeveloperRepository.
 *
 * This file used to handling all developer related activities, which all in DeveloperInterface
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
        return $this->developerModel->findOrFail($id);
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
        $developerInfo['firstname'] = $request['email'];
        $developerInfo['lastname'] = $request['lastname'];
        $developerInfo['email'] = $request['email'];
        $developerInfo['phone'] = $request['phone'];
        $developerInfo['address'] = $request['address'];
        $developerInfo['avatar'] = $request['avatar'];

        return $this->profileModel::create($profileInfo);
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
        $developer = $this->developerModel->findOrFail($id);
        $developerFlag = false;

        if ($developer->firstname != $request['firstname']) {
            $developerFlag = true;
            $developer->firstname = $request['firstname'];
        }

        if ($developer->lastname != $request['lastname']) {
            $developerFlag = true;
            $developer->lastname = $request['lastname'];
        }

        if ($developer->email != $request['email']) {
            $developerFlag = true;
            $developer->email = $request['email'];
        }

        if ($developer->phone != $request['phone']) {
            $developerFlag = true;
            $developer->phone = $request['phone'];
        }

        if ($developer->address != $request['address']) {
            $developerFlag = true;
            $developer->address = $request['address'];
        }

        if ($developer->avatar != $request['avatar']) {
            $developerFlag = true;
            $developer->avatar = $request['avatar'];
        }

        if ($developerFlag) {
            $developer->updated_at = Carbon::now();

            $developer->update();
        }

        return true;
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
        $developer = $this->developerModel::findOrFail($id);

        return $developer->delete();
    }
}

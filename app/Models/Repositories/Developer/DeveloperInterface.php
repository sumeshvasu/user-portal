<?php
/**
 * Interface : DeveloperInterface.
 *
 * This file used to initialise all admin related activities
 *
 * @author Sumesh K V <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */

namespace App\Models\Repositories\Developer;

use Illuminate\Http\Request;

interface DeveloperInterface
{
    /**
     * Get role list.
     */
    public function list();

    /**
     * Display the specified resource.
     */
    public function show($id);

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request);

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id);

    /**
     * Update the specified resource status.
     */
    public function updateStatus(Request $request, $id);

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id);
}

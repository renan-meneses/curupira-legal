<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Organizations\OrganizationRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class OrganizationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->organizations()->orderBy('name')->get(['organizations.id', 'organizations.name', 'organizations.slug', 'organizations.status'])]);
    }

    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organization = DB::transaction(function () use ($request): Organization {
            $name = $request->validated('name');
            $slug = Str::slug($name);
            $baseSlug = $slug;
            $suffix = 2;
            while (Organization::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            $organization = Organization::query()->create(['name' => $name, 'slug' => $slug]);
            $organization->users()->attach($request->user()->getAuthIdentifier(), ['role' => OrganizationRole::Administrator->value, 'permissions' => json_encode([]), 'joined_at' => now()]);
            return $organization;
        });

        $request->session()->put('active_organization_id', $organization->getKey());
        return response()->json(['data' => $organization], 201);
    }

    public function switch(Request $request, Organization $organization): JsonResponse
    {
        abort_unless($request->user()->organizations()->whereKey($organization->getKey())->exists(), 403, 'Você não possui acesso a esta organização.');
        $request->session()->put('active_organization_id', $organization->getKey());
        return response()->json(['data' => $organization->only(['id', 'name', 'slug', 'status'])]);
    }
}

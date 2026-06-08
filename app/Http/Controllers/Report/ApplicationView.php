<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationBlock;
use App\Models\ApplicationFlow;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Cartographer;
use App\Models\Database;
use Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ApplicationView extends Controller
{
    /**
     * Prepare data for the applications report view based on the requested application block and application.
     *
     * Persists selected `applicationBlock` and `application` values in session, applies those selections to filter
     * application-related collections (blocks, applications, services, modules, databases, and fluxes), and returns
     * the view used to render the applications report. Access is denied with a 403 response when the current user
     * lacks the `reports_access` permission.
     *
     * @param  \Illuminate\Http\Request  $request  Request that may contain `applicationBlock` and `application` parameters used to filter results; values are stored in session when present.
     * @return \Illuminate\View\View A view for 'admin/reports/applications' populated with the following keys: `all_applicationBlocks`, `all_applications`, `applicationBlocks`, `applications`, `applicationServices`, `applicationModules`, `databases`, and `fluxes`.
     */
    public function generate(Request $request): View
    {
        $allowed = Gate::allows('explore_access') || Cartographer::canAccessAny([ApplicationBlock::class, Application::class, ApplicationService::class, ApplicationModule::class, Database::class, ApplicationFlow::class]);
        abort_if(!$allowed, Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->applicationBlock == null) {
            $request->session()->put('applicationBlock', null);
            $applicationBlock = null;
            $request->session()->put('application', null);
            $application = null;
        } else {
            if ($request->applicationBlock != null) {
                $applicationBlock = intval($request->applicationBlock);
                $request->session()->put('applicationBlock', $applicationBlock);
            } else {
                $applicationBlock = $request->session()->get('applicationBlock');
            }

            if ($request->application == null) {
                $request->session()->put('application', null);
                $application = null;
            } elseif ($request->application != null) {
                $application = intval($request->application);
                $request->session()->put('application', $application);
            } else {
                $application = $request->session()->get('application');
            }
        }

        $all_applicationBlocks = Cartographer::scopedQuery(ApplicationBlock::query())->orderBy('name')->get();

        if ($applicationBlock !== null) {
            $applicationBlocks = Cartographer::scopedQuery(ApplicationBlock::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->id === $applicationBlock;
                });

            $applications = Cartographer::scopedQuery(Application::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application !== null) {
                        return $item->id === $application;
                    }

                    return $item->application_block_id = $applicationBlock;
                });

            $all_applications = Cartographer::scopedQuery(Application::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->application_block_id === $applicationBlock;
                });

            $applications = Cartographer::scopedQuery(Application::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application === null) {
                        return $item->application_block_id === $applicationBlock;
                    }

                    return $item->id === $application;
                });

            $applicationServices = Cartographer::scopedQuery(ApplicationService::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applications) {
                    foreach ($applications as $application) {
                        foreach ($application->services as $service) {
                            if ($item->id === $service->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $applicationModules = Cartographer::scopedQuery(ApplicationModule::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applicationServices) {
                    foreach ($applicationServices as $service) {
                        foreach ($service->modules as $module) {
                            if ($item->id === $module->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $databases = Cartographer::scopedQuery(Database::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applications) {
                    foreach ($applications as $application) {
                        foreach ($application->databases as $database) {
                            if ($item->id === $database->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $flows = Cartographer::scopedQuery(ApplicationFlow::query())->get()->sortBy('name')
                ->filter(function ($item) use ($applications, $applicationModules, $databases) {
                    foreach ($applications as $application) {
                        if ($item->application_source_id === $application->id) {
                            return true;
                        }
                        if ($item->application_dest_id === $application->id) {
                            return true;
                        }
                    }
                    foreach ($applicationModules as $module) {
                        if ($item->module_source_id === $module->id) {
                            return true;
                        }
                        if ($item->module_dest_id === $module->id) {
                            return true;
                        }
                    }
                    foreach ($databases as $database) {
                        if ($item->database_source_id === $database->id) {
                            return true;
                        }
                        if ($item->database_dest_id === $database->id) {
                            return true;
                        }
                    }

                    return false;
                });
        } else {
            $applicationBlocks = Cartographer::scopedQuery(ApplicationBlock::query())->orderBy('name')->get();
            $applications = Cartographer::scopedQuery(Application::query())->orderBy('name')->get();
            $applicationServices = Cartographer::scopedQuery(ApplicationService::query())->orderBy('name')->get();
            $applicationModules = Cartographer::scopedQuery(ApplicationModule::query())->orderBy('name')->get();
            $databases = Cartographer::scopedQuery(Database::query())->orderBy('name')->get();
            $flows = Cartographer::scopedQuery(ApplicationFlow::query())->orderBy('name')->get();
            $all_applications = null;
        }

        return view('admin/reports/applications')
            ->with('all_applicationBlocks', $all_applicationBlocks)
            ->with('all_applications', $all_applications)
            ->with('applicationBlocks', $applicationBlocks)
            ->with('applications', $applications)
            ->with('applicationServices', $applicationServices)
            ->with('applicationModules', $applicationModules)
            ->with('databases', $databases)
            ->with('flows', $flows);
    }
}

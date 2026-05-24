import {
    Cell,
    CellEditorHandler,
    eventUtils,
    FastOrganicLayout,
    Graph,
    GraphDataModel,
    type GraphPluginConstructor,
    InternalEvent,
    ModelXmlSerializer,
    Morphing,
    PanningHandler,
    RubberBandHandler,
    SelectionCellsHandler,
    SelectionHandler,
    styleUtils,
    UndoManager,
    VertexHandlerConfig,
} from '@maxgraph/core';

//-----------------------------------------------------------------------
// Interfaces métier
// Note: "Node" est volontairement renommé MapNode pour ne pas masquer le DOM Node.

interface Edge {
    attachedNodeId: string;
    name: string;
    edgeType: string;
    edgeDirection: string;
    bidirectional: boolean;
}

interface MapNode {
    id: string;
    vue: string;
    label: string;
    image: string;
    type: string;
    edges: Edge[];
}

type NodeMap = Map<string, MapNode>;

// Déclaration de globals fournies par ailleurs
declare const _nodes: NodeMap;
declare const $: any;

//-----------------------------------------------------------------------
// Plugins MaxGraph

const plugins: GraphPluginConstructor[] = [
    // L'ordre est important
    CellEditorHandler,
    SelectionCellsHandler,
    SelectionHandler,
    PanningHandler,
    RubberBandHandler,
];

// Initialisation du graph

const container = document.getElementById('graph-container') as HTMLDivElement | null;
if (!container) {
    throw new Error('#graph-container introuvable');
}

const graph = new Graph(container, new GraphDataModel(), plugins);
const model = graph.getDataModel();

//-----------------------------------------------------------------------
// Style des arêtes

const edgeDefaultStyle = graph.getStylesheet().getDefaultEdgeStyle();
edgeDefaultStyle.labelBackgroundColor = '#FFFFFF';
edgeDefaultStyle.strokeWidth = 2;
edgeDefaultStyle.rounded = true;
edgeDefaultStyle.entryPerimeter = false;
edgeDefaultStyle.edgeStyle = 'manhattanEdgeStyle';

// Désactiver le folding
(graph as any).getFoldingImage = () => null;

// Sélection des sommets
VertexHandlerConfig.selectionColor = '#00a8ff';
VertexHandlerConfig.selectionStrokeWidth = 2;

//-----------------------------------------------------------------------
// Undo / Redo

const undoManager = new UndoManager();
const undoListener = (_sender: unknown, evt: any) => {
    const edit = evt.getProperty('edit');
    if (edit) {
        undoManager.undoableEditHappened(edit);
    }
};

model.addListener(InternalEvent.UNDO, undoListener);
graph.getView().addListener(InternalEvent.UNDO, undoListener);

const undoButton = document.getElementById('undoButton') as HTMLButtonElement | null;
const redoButton = document.getElementById('redoButton') as HTMLButtonElement | null;

if (undoButton) {
    undoButton.addEventListener('click', () => {
        if (undoManager.canUndo()) undoManager.undo();
    });
}

if (redoButton) {
    redoButton.addEventListener('click', () => {
        if (undoManager.canRedo()) undoManager.redo();
    });
}

document.addEventListener('keydown', (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'z') {
        event.preventDefault();
        if (undoManager.canUndo()) undoManager.undo();
    } else if (event.ctrlKey && event.key === 'y') {
        event.preventDefault();
        if (undoManager.canRedo()) undoManager.redo();
    }
});

// --------------------------------------------------------------------------------
// Menus contextuels

const MENU_OFFSET_X = 75;
const MENU_OFFSET_Y = 100;

const edgeContextMenu  = document.getElementById('edge-context-menu')  as HTMLDivElement  | null;
const edgeColorSelect  = document.getElementById('edge-color-select')  as HTMLInputElement | null;
const thicknessSelect  = document.getElementById('edge-thickness-select') as HTMLSelectElement | null;

const textContextMenu     = document.getElementById('text-context-menu')     as HTMLDivElement    | null;
const textFontSelect      = document.getElementById('text-font-select')      as HTMLSelectElement | null;
const textColorSelect     = document.getElementById('text-color-select')     as HTMLInputElement  | null;
const textSizeSelect      = document.getElementById('text-size-select')      as HTMLSelectElement | null;
const textBoldSelect      = document.getElementById('text-bold-select')      as HTMLButtonElement | null;
const textItalicSelect    = document.getElementById('text-italic-select')    as HTMLButtonElement | null;
const textUnderlineSelect = document.getElementById('text-underline-select') as HTMLButtonElement | null;

let selectedCell: Cell | null = null;

function hideContextMenus(): void {
    if (textContextMenu) textContextMenu.style.display = 'none';
    if (edgeContextMenu) edgeContextMenu.style.display = 'none';
}

function showEdgeMenu(x: number, y: number, style: any): void {
    if (!edgeContextMenu || !textContextMenu) return;
    edgeContextMenu.style.display = 'block';
    edgeContextMenu.style.left    = `${x + MENU_OFFSET_X}px`;
    edgeContextMenu.style.top     = `${y + MENU_OFFSET_Y}px`;
    if (edgeColorSelect) edgeColorSelect.value = style.strokeColor ?? '#000000';
    if (thicknessSelect) thicknessSelect.value = String(style.strokeWidth ?? '1');
    textContextMenu.style.display = 'none';
}

function showTextMenu(x: number, y: number, style: any, cellStyle: any): void {
    if (!edgeContextMenu || !textContextMenu) return;
    textContextMenu.style.display = 'block';
    textContextMenu.style.left    = `${x + MENU_OFFSET_X}px`;
    textContextMenu.style.top     = `${y + MENU_OFFSET_Y}px`;
    if (textColorSelect) textColorSelect.value = style.fontColor  ?? '#000000';
    if (textFontSelect)  textFontSelect.value  = style.fontFamily ?? 'Arial';
    if (textSizeSelect)  textSizeSelect.value  = String(style.fontSize ?? '12');
    edgeContextMenu.style.display = 'none';

    const fontStyle = cellStyle?.fontStyle ?? 0;
    textBoldSelect?.classList.toggle('selected',      !!(fontStyle & 1));
    textItalicSelect?.classList.toggle('selected',    !!(fontStyle & 2));
    textUnderlineSelect?.classList.toggle('selected', !!(fontStyle & 4));
}

graph.container.addEventListener('contextmenu', (event: MouseEvent) => {
    event.preventDefault();

    const cell = graph.getCellAt(event.offsetX, event.offsetY) as Cell | null;
    if (!cell) return;

    const rect        = container.getBoundingClientRect();
    const x           = event.clientX - rect.left;
    const y           = event.clientY - rect.top;
    const currentStyle = graph.getCellStyle(cell) as any;

    if (cell.isEdge()) {
        selectedCell = cell;
        showEdgeMenu(x, y, currentStyle);
    } else if (cell.isVertex()) {
        const cellValue = cell.value as string | null;
        const hasText   = !!cellValue && cellValue.trim() !== '';
        const cellStyle = cell.style as any;

        if (hasText && textColorSelect && textFontSelect && textSizeSelect) {
            selectedCell = cell;
            showTextMenu(x, y, currentStyle, cellStyle);
        } else if (!cellStyle?.image && (!cell.children || cell.children.length === 0)) {
            selectedCell = cell;
            showEdgeMenu(x, y, currentStyle);
        } else {
            hideContextMenus();
        }
    } else {
        hideContextMenus();
    }
});

document.getElementById('apply-edge-style')?.addEventListener('click', (e) => {
    e.preventDefault();
    if (!selectedCell || !edgeColorSelect || !thicknessSelect) return;

    graph.batchUpdate(() => {
        const style     = (selectedCell!.style ?? {}) as any;
        const thickness = parseInt(thicknessSelect!.value, 10) || 1;

        if (selectedCell!.isEdge()) {
            style.strokeColor = edgeColorSelect!.value;
        } else {
            style.fillColor = edgeColorSelect!.value;
        }
        style.strokeWidth     = thickness;
        selectedCell!.style   = style;
        graph.refresh(selectedCell!);
    });

    if (edgeContextMenu) edgeContextMenu.style.display = 'none';
});

document.getElementById('apply-text-style')?.addEventListener('click', (e) => {
    e.preventDefault();
    if (!selectedCell || !textFontSelect || !textColorSelect || !textSizeSelect) return;

    graph.batchUpdate(() => {
        const style = (selectedCell!.style ?? {}) as any;

        style.fontFamily = textFontSelect!.value;
        style.fontColor  = textColorSelect!.value;
        style.fontSize   = parseInt(textSizeSelect!.value, 10) || 12;

        let flag = 0;
        if (textBoldSelect?.classList.contains('selected'))      flag |= 1;
        if (textItalicSelect?.classList.contains('selected'))    flag |= 2;
        if (textUnderlineSelect?.classList.contains('selected')) flag |= 4;
        style.fontStyle = flag;

        selectedCell!.style = style;
        graph.refresh(selectedCell!);
    });

    if (textContextMenu) textContextMenu.style.display = 'none';
});

// Boutons avec classe .button → toggle "selected"
document
    .querySelectorAll<HTMLButtonElement>('.button')
    .forEach((button) => {
        button.addEventListener('click', () => button.classList.toggle('selected'));
    });

// Cacher les menus contextuels en cliquant ailleurs
document.addEventListener('click', (event) => {
    const target = event.target as globalThis.Node | null;
    if (textContextMenu && !textContextMenu.contains(target)) textContextMenu.style.display = 'none';
    if (edgeContextMenu && !edgeContextMenu.contains(target)) edgeContextMenu.style.display = 'none';
});

// --------------------------------------------------------------------------------
// Grille

graph.setGridEnabled(true);
graph.setGridSize(10);

container.style.backgroundImage = `
  linear-gradient(to right, #e0e0e0 1px, transparent 1px),
  linear-gradient(to bottom, #e0e0e0 1px, transparent 1px)
`;
container.style.backgroundSize = '10px 10px';

// -----------------------------------------------------------------------
// Panning

graph.setPanning(true);
(graph as any).allowAutoPanning       = true;
(graph as any).useScrollbarsForPanning = true;

//-------------------------------------------------------------------------
// LOAD / SAVE

export function loadGraph(xml: string) {
    new ModelXmlSerializer(model).import(xml);
}

(window as any).loadGraph = loadGraph;

async function saveGraphToDatabase(
    id: number | string,
    name: string,
    type: string,
    content: string,
): Promise<void> {
    const csrfMeta  = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
    const csrfToken = csrfMeta?.content;
    if (!csrfToken) {
        console.error('CSRF token manquant');
        alert('Token CSRF manquant.');
        return;
    }

    try {
        const response = await fetch(`/admin/graphs/${id}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type':      'application/json',
                Accept:              'application/json',
                'X-CSRF-TOKEN':      csrfToken,
                'X-Requested-With':  'XMLHttpRequest',
            },
            body: JSON.stringify({ _method: 'PUT', id, name, type, content }),
        });

        if (response.status !== 200) {
            let errorMessage = 'Erreur lors de la sauvegarde du graphe.';
            try {
                const error = await response.json();
                if (error?.message) errorMessage = error.message;
            } catch { /* ignore */ }
            throw new Error(errorMessage);
        }
    } catch (error) {
        console.error('Erreur lors de la sauvegarde :', error);
        alert('Erreur lors de la sauvegarde du graphe.');
    }
}

function getXMLGraph(): string {
    return new ModelXmlSerializer(graph.getDataModel()).export();
}

(window as any).getXMLGraph = getXMLGraph;

function saveGraph() {
    const idInput   = document.querySelector('#id')   as HTMLInputElement | null;
    const nameInput = document.querySelector('#name') as HTMLInputElement | null;
    const typeInput = document.querySelector('#type') as HTMLInputElement | null;

    if (!idInput || !nameInput || !typeInput) {
        alert('Champs id / name / type manquants');
        return;
    }

    saveGraphToDatabase(idInput.value, nameInput.value, typeInput.value, new ModelXmlSerializer(model).export());
}

document.getElementById('saveButton')?.addEventListener('click', saveGraph);

//-------------------------------------------------------------------------
// Utilitaires

type Point = { x: number; y: number };

function getGraphPointFromEvent(graph: Graph, evt: MouseEvent | DragEvent): Point {
    if (typeof (graph as any).getPointForEvent === 'function') {
        const pt = (graph as any).getPointForEvent(evt as any);
        return { x: pt.x, y: pt.y };
    }

    const view  = (graph as any).view ?? graph.getView();
    const pt    = styleUtils.convertPoint(
        graph.container,
        eventUtils.getClientX(evt as any),
        eventUtils.getClientY(evt as any),
    );
    const tr    = view.translate ?? { x: 0, y: 0 };
    const scale = view.scale ?? 1;
    const panDx = (graph as any).panDx ?? 0;
    const panDy = (graph as any).panDy ?? 0;

    return {
        x: (pt.x - panDx) / scale - tr.x - 20,
        y: (pt.y - panDy) / scale - tr.y - 20,
    };
}

function getFilter(): string[] {
    const select = document.getElementById('filters') as HTMLSelectElement | null;
    if (!select) return [];
    return Array.from(select.options).filter(o => o.selected).map(o => o.value);
}

function hasEdge(src: Cell | null, dest: Cell | null, name: string | null): boolean {
    if (!src || !dest) return false;
    const nameMatches = (edge: Cell) => name == null || edge.value === name;
    return (
        graph.getEdges(src).some(e  => e.target === dest && nameMatches(e)) ||
        graph.getEdges(dest).some(e => e.target === src  && nameMatches(e))
    );
}

function buildEdgeStyle(edge: Edge): object {
    const isFlux = edge.edgeType === 'FLUX';
    return {
        editable:    false,
        strokeColor: '#000000',
        strokeWidth: 1,
        startArrow:  isFlux && (edge.bidirectional || edge.edgeDirection === 'FROM') ? 'classic' : 'none',
        endArrow:    isFlux && (edge.bidirectional || edge.edgeDirection === 'TO')   ? 'classic' : 'none',
    };
}

graph.enterStopsCellEditing = true;

//-------------------------------------------------------------------------
// Drag & drop (gestionnaire unique)

const fontBtn     = document.getElementById('font-btn')   as HTMLElement    | null;
const squareIcon  = document.getElementById('square-btn') as HTMLElement    | null;
const nodeIcon    = document.getElementById('nodeImage')  as HTMLImageElement | null;
const nodeSelector = document.getElementById('node')     as HTMLSelectElement | null;

fontBtn?.addEventListener('dragstart',    (e: DragEvent) => e.dataTransfer?.setData('node-type', 'text-node'));
squareIcon?.addEventListener('dragstart', (e: DragEvent) => e.dataTransfer?.setData('node-type', 'square-node'));
nodeIcon?.addEventListener('dragstart',   (e: DragEvent) => e.dataTransfer?.setData('node-type', 'icon-node'));

container.addEventListener('dragover', (event: DragEvent) => event.preventDefault());

container.addEventListener('drop', (event: DragEvent) => {
    event.preventDefault();
    const type = event.dataTransfer?.getData('node-type');
    if (!type) return;

    const pt     = getGraphPointFromEvent(graph, event);
    const parent = graph.getDefaultParent();

    if (type === 'text-node') {
        graph.batchUpdate(() => {
            graph.insertVertex({
                parent,
                value:    'Text',
                position: [pt.x, pt.y],
                size:     [150, 30],
                style: {
                    fillColor:     'none',
                    strokeColor:   'none',
                    fontColor:     '#000000',
                    fontSize:      14,
                    align:         'left',
                    verticalAlign: 'middle',
                },
            });
        });
        return;
    }

    if (type === 'square-node') {
        graph.batchUpdate(() => {
            const vertex = graph.insertVertex({
                parent,
                value:    '',
                position: [pt.x, pt.y],
                size:     [150, 120],
                style: {
                    fillColor:   '#fffacd',
                    strokeColor: '#000000',
                    strokeWidth: 1,
                    rounded:     2,
                },
            });
            graph.orderCells(true, [vertex]);
        });
        return;
    }

    if (type === 'icon-node' && nodeIcon?.src && nodeSelector) {
        const nodeId = nodeSelector.value;
        graph.batchUpdate(() => {
            const existing = model.getCell(nodeId) as Cell | null;
            if (existing) {
                graph.setSelectionCells([existing]);
                return;
            }

            const node = _nodes.get(nodeId);
            if (!node) return;

            const newVertex = graph.insertVertex({
                parent,
                id:       nodeId,
                value:    node.label,
                position: [pt.x - 16, pt.y - 16],
                size:     [32, 32],
                style: {
                    shape:                 'image',
                    image:                 nodeIcon.src,
                    editable:              false,
                    resizable:             true,
                    verticalLabelPosition: 'bottom',
                    spacingTop:            -15,
                },
            });

            node.edges.forEach((edge) => {
                const targetCell = model.getCell(edge.attachedNodeId) as Cell | null;
                if (targetCell) {
                    graph.insertEdge({
                        parent,
                        value:  '',
                        source: newVertex,
                        target: targetCell,
                        style: {
                            editable:    false,
                            strokeColor: '#ff0000',
                            strokeWidth: 1,
                            startArrow:  'none',
                            endArrow:    'none',
                        },
                    });
                }
            });
        });
    }
});

//-------------------------------------------------------------------------
// Zoom

const zoomInButton  = document.getElementById('zoom-in-btn')  as HTMLButtonElement | null;
const zoomOutButton = document.getElementById('zoom-out-btn') as HTMLButtonElement | null;

if (zoomInButton)  zoomInButton.addEventListener('click',  () => graph.zoomIn());
if (zoomOutButton) zoomOutButton.addEventListener('click', () => graph.zoomOut());

//-------------------------------------------------------------------------
// Suppression avec Delete / Backspace

document.addEventListener('keydown', (event: KeyboardEvent) => {
    if (event.key === 'Delete' || event.key === 'Backspace') {
        const cells = graph.getSelectionCells();
        if (cells.length > 0) graph.removeCells(cells);
    }
});

//-------------------------------------------------------------------------
// CTRL+A : sélectionner tout

document.addEventListener('keydown', (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'a') {
        event.preventDefault();
        event.stopPropagation();
        graph.selectAll();
    }
});

//-------------------------------------------------------------------------
// Connexions / déconnexions

graph.setConnectable(false);
(graph as any).isCellDisconnectable = () => false;

//-------------------------------------------------------------------------
// Group / ungroup

const groupButton   = document.getElementById('group-btn')   as HTMLButtonElement | null;
const ungroupButton = document.getElementById('ungroup-btn') as HTMLButtonElement | null;

if (groupButton) {
    groupButton.addEventListener('click', () => {
        const cells = graph.getSelectionCells();
        if (cells.length > 1) {
            const parent = graph.getDefaultParent();
            // insertVertex ajoute déjà le groupe au parent — pas besoin de addCell()
            const group = graph.insertVertex({
                parent,
                style: { fillColor: 'none', strokeColor: 'none' },
            });
            graph.groupCells(group, 5, cells);
        }
    });
}

if (ungroupButton) {
    ungroupButton.addEventListener('click', () => {
        const cells = graph.getSelectionCells();
        if (cells.length === 1) {
            graph.ungroupCells(cells);
            graph.setSelectionCells(cells);
        }
    });
}

//---------------------------------------------------------------------------
// Déplacement avec flèches

function moveSelectedVertex(graph: Graph, dx: number, dy: number) {
    const selected = graph.getSelectionCell() as Cell | null;
    if (!selected?.isVertex()) return;
    graph.batchUpdate(() => {
        const geo = selected.getGeometry();
        if (geo) {
            geo.translate(dx, dy);
            graph.refresh();
        }
    });
}

document.addEventListener('keydown', (event: KeyboardEvent) => {
    const step = 1;
    switch (event.key) {
        case 'ArrowUp':    moveSelectedVertex(graph, 0, -step); break;
        case 'ArrowDown':  moveSelectedVertex(graph, 0,  step); break;
        case 'ArrowLeft':  moveSelectedVertex(graph, -step, 0); break;
        case 'ArrowRight': moveSelectedVertex(graph,  step, 0); break;
    }
});

//---------------------------------------------------------------------------
// Placement en cercle (double-clic)

function placeObjectsOnCircle(center: Point, radius: number, n: number): Point[] {
    const angleStep = (2 * Math.PI) / n;
    return Array.from({ length: n }, (_, i) => ({
        x: center.x + radius * Math.cos(i * angleStep),
        y: center.y + radius * Math.sin(i * angleStep),
    }));
}

//----------------------------------------------------------------
// Double-clic sur icône

graph.addListener(InternalEvent.DOUBLE_CLICK, (_sender, evt) => {
    const cell = evt.getProperty('cell') as Cell | null;
    if (!cell?.isVertex()) return;

    const style = cell.style as any;
    if (style?.shape !== 'image') return;

    const node = _nodes.get(cell.id as string);
    if (!node) return;

    graph.batchUpdate(() => {
        const newEdges: Edge[] = [];
        const parent           = graph.getDefaultParent();
        const filter           = getFilter();

        node.edges.forEach((edge) => {
            const targetNode = _nodes.get(edge.attachedNodeId);
            if (!targetNode) return;

            const vertex = model.getCell(edge.attachedNodeId) as Cell | null;

            if (!vertex && !newEdges.some((e) => e.attachedNodeId === edge.attachedNodeId)) {
                if (
                    filter.length === 0 ||
                    filter.includes(targetNode.vue) ||
                    (filter.includes('8') && edge.edgeType === 'CABLE') ||
                    (filter.includes('9') && edge.edgeType === 'FLUX')
                ) {
                    newEdges.push(edge);
                }
            } else if (vertex && !hasEdge(cell, vertex, edge.name)) {
                graph.insertEdge({
                    parent,
                    value:  edge.name,
                    source: cell,
                    target: vertex,
                    style:  buildEdgeStyle(edge),
                });
            }
        });

        const geom = cell.getGeometry();
        if (!geom || newEdges.length === 0) return;

        const positions = placeObjectsOnCircle({ x: geom.x, y: geom.y }, 80, newEdges.length);

        for (let i = 0; i < positions.length; i++) {
            const edge    = newEdges[i];
            const newNode = _nodes.get(edge.attachedNodeId);
            if (!newNode) continue;

            const vertex = graph.insertVertex({
                parent,
                id:       newNode.id,
                value:    newNode.label,
                position: [positions[i].x, positions[i].y],
                size:     [32, 32],
                style: {
                    shape:                 'image',
                    image:                 newNode.image,
                    editable:              false,
                    resizable:             true,
                    verticalLabelPosition: 'bottom',
                    spacingTop:            -15,
                },
            });

            graph.insertEdge({
                parent,
                value:  edge.name,
                source: cell,
                target: vertex,
                style:  buildEdgeStyle(edge),
            });
        }
    });
});

//-------------------------------------------------------------------------
// Mise à jour des nœuds depuis _nodes

document.getElementById('update-btn')?.addEventListener('click', () => {
    graph.batchUpdate(() => {
        graph.getChildCells().forEach((cell) => {
            if (cell.isEdge()) return;
            const style = cell.style as any;
            if (!style?.image) return;

            const node = _nodes.get(cell.id as string);
            if (!node) {
                graph.removeCells([cell], true);
            } else {
                cell.value = node.label;
                styleUtils.setCellStyles(graph.getDataModel(), [cell], 'image', node.image);
            }
        });
        graph.refresh();
    });
});

//---------------------------------------------------------------------------
// Export SVG

const svgElement = graph.container.querySelector('svg') as SVGSVGElement | null;

async function embedImagesInSVG(svg: SVGSVGElement): Promise<void> {
    const images = Array.from(svg.querySelectorAll('image'));
    await Promise.all(images.map(async (img) => {
        const href = img.getAttribute('xlink:href') ?? img.getAttribute('href');
        if (!href || href.startsWith('data:')) return;
        try {
            const response = await fetch(href);
            const blob     = await response.blob();
            const dataUrl  = await new Promise<string>((resolve, reject) => {
                const reader      = new FileReader();
                reader.onloadend  = () => resolve(reader.result as string);
                reader.onerror    = reject;
                reader.readAsDataURL(blob);
            });
            img.setAttribute('href', dataUrl);
            img.removeAttribute('xlink:href');
        } catch (err) {
            console.error('Erreur embed image SVG', err);
        }
    }));
}

async function downloadSVG(): Promise<void> {
    if (!svgElement) {
        alert('SVG introuvable');
        return;
    }

    await embedImagesInSVG(svgElement);

    const serializer = new XMLSerializer();
    const svgString  = serializer.serializeToString(svgElement);
    const blob       = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
    const url        = URL.createObjectURL(blob);

    const now = new Date();
    const timestamp =
        now.getFullYear() +
        String(now.getMonth() + 1).padStart(2, '0') +
        String(now.getDate()).padStart(2, '0') +
        String(now.getHours()).padStart(2, '0') +
        String(now.getMinutes()).padStart(2, '0');

    const link      = document.createElement('a');
    link.href       = url;
    link.download   = `graph-${timestamp}.svg`;
    link.click();
    URL.revokeObjectURL(url);
}

document.getElementById('download-btn')?.addEventListener('click', downloadSVG);

//-------------------------------------------------------------------------
// Layout organic

export function layout() {
    const parent = graph.getDefaultParent();
    const cells  = graph.getChildVertices(parent);
    if (!cells || cells.length === 0) return;

    const organic           = new FastOrganicLayout(graph);
    organic.forceConstant   = 60;
    organic.disableEdgeStyle = false;

    graph.getDataModel().beginUpdate();
    try {
        organic.execute(parent, cells);
    } finally {
        const morph = new Morphing(graph);
        morph.addListener(InternalEvent.DONE, () => graph.getDataModel().endUpdate());
        morph.startAnimation();
    }
}

document.getElementById('layout-btn')?.addEventListener('click', layout);

import './bootstrap';

const menuToggle = document.querySelector('.menu-toggle');
const siteNav = document.querySelector('.site-nav');

menuToggle?.addEventListener('click', () => {
    const isOpen = siteNav?.classList.toggle('open') ?? false;
    menuToggle.setAttribute('aria-expanded', String(isOpen));
});

const npc = document.querySelector('.npc-companion');
const spriteCanvas = document.querySelector('.wandering-sprite');
const speech = document.querySelector('.npc-speech');

if (npc && spriteCanvas && speech) {
    const context = spriteCanvas.getContext('2d');
    const spriteSheet = new Image();
    const walkSheet = new Image();
    const state = {
        mode: 'IDLE',
        x: 0,
        y: 0,
        vx: 0,
        vy: 0,
        direction: 1,
        platform: null,
        target: null,
        actionStartedAt: 0,
        landingUntil: 0,
        animFrameIndex: 0,
        animTimer: 0,
        animState: null,
        platformIndex: 0,
    };
    const frames = [[], [], [], [], []];
    const frameLoops = {
        IDLE: [0, 1, 4, 3],
        WALK: [0, 1, 2, 3, 4, 5],
        JUMP_UP: [1, 2],
        JUMP_FALL: [3],
        ACTION: [3, 4, 6, 7, 5],
        DEFEAT: [0, 1, 2, 3, 4],
    };
    const rowFrameCounts = [8, 7, 5, 8, 5];
    const gravity = 1850;
    let speechTimer;
    let lastTimestamp = 0;
    let initialized = false;
    let mainReady = false;
    let walkReady = false;

    const say = (message) => {
        speech.textContent = message;
        speech.classList.add('visible');
        window.clearTimeout(speechTimer);
        speechTimer = window.setTimeout(() => speech.classList.remove('visible'), 1800);
    };

    const setState = (mode) => {
        state.mode = mode;
        state.animFrameIndex = 0;
        state.animTimer = 0;
        state.animState = null;
    };

    const pageBounds = (element) => {
        const bounds = element.getBoundingClientRect();
        return {
            element,
            left: bounds.left + window.scrollX,
            right: bounds.right + window.scrollX,
            top: bounds.top + window.scrollY,
            width: bounds.width,
        };
    };

    const platforms = () => [...document.querySelectorAll('[data-platform]')]
        .map(pageBounds)
        .filter((platform) => platform.width > 40);

    const drawFrame = (row, frameIndex) => {
        const frame = frames[row]?.[frameIndex];
        if (!frame || !context) {
            return;
        }

        context.imageSmoothingEnabled = false;
        context.clearRect(0, 0, spriteCanvas.width, spriteCanvas.height);
        context.save();
        context.drawImage(frame, 0, 0);
        context.restore();
    };

    const drawAnimation = (timestamp, deltaMs) => {
        let animationMode = state.mode === 'PATROL' ? 'WALK' : state.mode;

        if (state.landingUntil > timestamp) {
            drawFrame(2, 4);
            return;
        }

        if (state.mode === 'SEEK') {
            animationMode = state.vy < 0 ? 'JUMP_UP' : 'JUMP_FALL';
        } else if (state.mode === 'FALL') {
            animationMode = 'DEFEAT';
        } else if (state.mode === 'ACTION') {
            animationMode = 'ACTION';
        } else if (state.mode === 'IDLE') {
            animationMode = 'IDLE';
        }

        const loop = frameLoops[animationMode];
        const fps = animationMode === 'WALK' ? 10 : animationMode === 'ACTION' ? 12 : 4;
        const row = animationMode === 'WALK' ? 1
            : animationMode === 'ACTION' ? 3
                : animationMode === 'DEFEAT' ? 4 : animationMode.startsWith('JUMP') ? 2 : 0;

        if (animationMode === 'JUMP_UP') {
            drawFrame(row, loop[Math.min(state.animFrameIndex, loop.length - 1)]);
            state.animTimer += deltaMs;
            if (state.animTimer >= 1000 / fps) {
                state.animTimer = 0;
                state.animFrameIndex = Math.min(state.animFrameIndex + 1, loop.length - 1);
            }
            return;
        }

        if (animationMode === 'JUMP_FALL') {
            drawFrame(row, 3);
            return;
        }

        if (state.animState !== animationMode) {
            state.animState = animationMode;
            state.animFrameIndex = 0;
            state.animTimer = 0;
        }

        state.animTimer += deltaMs;
        while (state.animTimer >= 1000 / fps) {
            state.animTimer -= 1000 / fps;
            if (animationMode === 'ACTION' || animationMode === 'DEFEAT') {
                state.animFrameIndex = Math.min(state.animFrameIndex + 1, loop.length - 1);
            } else {
                state.animFrameIndex = (state.animFrameIndex + 1) % loop.length;
            }
        }

        if (animationMode === 'DEFEAT') {
            drawFrame(row, loop[state.animFrameIndex]);
            return;
        }

        drawFrame(row, loop[state.animFrameIndex]);
    };

    const render = () => {
        npc.style.transform = `translate3d(${state.x - window.scrollX - 32}px, ${state.y - window.scrollY - 64}px, 0)`;
        spriteCanvas.style.transform = state.direction < 0 ? 'scaleX(-1)' : 'scaleX(1)';
    };

    const choosePlatform = () => {
        const available = platforms();
        if (!available.length) {
            return null;
        }

        const visible = available.filter((platform) => {
            const viewportTop = platform.top - window.scrollY;
            return viewportTop < window.innerHeight + 80 && viewportTop > -100;
        });
        const choices = visible.length ? visible : available;
        const platform = choices[state.platformIndex % choices.length];
        state.platformIndex += 1;
        return platform;
    };

    const landOn = (platform) => {
        state.platform = platform;
        state.y = platform.top;
        state.x = Math.max(platform.left + 18, Math.min(state.x, platform.right - 18));
        state.vy = 0;
    };

    const startPatrol = (platform = state.platform) => {
        if (!platform) {
            return;
        }
        landOn(platform);
        state.vx = 70 * state.direction;
        setState('PATROL');
    };

    const startAction = (target) => {
        state.target = target;
        state.actionStartedAt = performance.now();
        state.vx = 0;
        setState('ACTION');
        say('Action complete!');
    };

    const seek = (element) => {
        const target = pageBounds(element);
        const targetPlatform = element.matches('[data-platform]')
            ? target
            : { ...target, left: target.left, right: target.right };
        const targetX = Math.max(target.left + 18, Math.min(
            target.left + target.width / 2,
            target.right - 18,
        ));
        const distance = targetX - state.x;
        state.target = targetPlatform;
        state.platform = targetPlatform;
        state.vx = Math.max(-480, Math.min(480, distance * 1.15));
        state.vy = -690;
        state.direction = state.vx < 0 ? -1 : 1;
        setState('SEEK');
        say('Following your move!');
    };

    const respawn = () => {
        const topPlatform = platforms()[0];
        if (!topPlatform) {
            return;
        }
        state.x = topPlatform.left + topPlatform.width / 2;
        state.platform = topPlatform;
        state.y = topPlatform.top;
        state.actionStartedAt = performance.now();
        setState('IDLE');
        say('Back on the page!');
    };

    const updatePhysics = (delta, timestamp) => {
        if (!state.platform && state.mode !== 'FALL') {
            startPatrol(choosePlatform());
        }

        if (state.mode === 'IDLE') {
            if (timestamp - state.actionStartedAt > 900) {
                startPatrol(state.platform);
            }
        } else if (state.mode === 'PATROL') {
            const platform = pageBounds(state.platform.element);
            state.platform = platform;
            state.x += state.vx * delta;

            if (state.x >= platform.right - 18) {
                state.x = platform.right - 18;
                state.direction = -1;
                state.vx = -70;
            } else if (state.x <= platform.left + 18) {
                state.x = platform.left + 18;
                state.direction = 1;
                state.vx = 70;
            }
            state.y = platform.top;
        } else if (state.mode === 'SEEK' || state.mode === 'FALL') {
            state.vy += gravity * delta;
            state.x += state.vx * delta;
            state.y += state.vy * delta;

            if (state.mode === 'SEEK' && state.vy >= 0 && state.y >= state.platform.top) {
                state.x = Math.max(state.platform.left + 18, Math.min(state.x, state.platform.right - 18));
                landOn(state.platform);
                state.landingUntil = timestamp + 240;
                startAction(state.target.element);
            } else if (state.y - window.scrollY > window.innerHeight + 100) {
                state.actionStartedAt = timestamp;
                setState('FALL');
            }
        } else if (state.mode === 'ACTION') {
            if (timestamp - state.actionStartedAt > 760) {
                startPatrol(state.platform);
            }
        }

        if (state.mode === 'FALL' && timestamp - state.actionStartedAt > 900) {
            respawn();
        }
    };

    const tick = (timestamp) => {
        const delta = Math.min(0.033, (timestamp - lastTimestamp) / 1000 || 0);
        lastTimestamp = timestamp;
        updatePhysics(delta, timestamp);
        drawAnimation(timestamp);
        render();
        window.requestAnimationFrame(tick);
    };

    const normalizeFrame = (sourceContext, pixelsInFrame, left, top, right, bottom) => {
        const targetHeight = 80;
        let minX = right - left;
        let minY = bottom - top;
        let maxX = 0;
        let maxY = 0;
        const width = right - left;
        const height = bottom - top;

        for (let y = 0; y < height; y += 1) {
            for (let x = 0; x < width; x += 1) {
                if (pixelsInFrame.data[((y * width + x) * 4) + 3] > 20) {
                    minX = Math.min(minX, x);
                    minY = Math.min(minY, y);
                    maxX = Math.max(maxX, x);
                    maxY = Math.max(maxY, y);
                }
            }
        }

        if (minX > maxX || minY > maxY) {
            return null;
        }

        const frameCanvas = document.createElement('canvas');
        frameCanvas.width = 96;
        frameCanvas.height = 96;
        const frameContext = frameCanvas.getContext('2d');
        frameContext.imageSmoothingEnabled = false;
        const spriteWidth = maxX - minX + 1;
        const spriteHeight = maxY - minY + 1;
        const frameImage = frameContext.createImageData(spriteWidth, spriteHeight);

        for (let y = 0; y < spriteHeight; y += 1) {
            for (let x = 0; x < spriteWidth; x += 1) {
                const sourceIndex = (((minY + y) * width + minX + x) * 4);
                const destinationIndex = ((y * spriteWidth + x) * 4);
                frameImage.data[destinationIndex] = pixelsInFrame.data[sourceIndex];
                frameImage.data[destinationIndex + 1] = pixelsInFrame.data[sourceIndex + 1];
                frameImage.data[destinationIndex + 2] = pixelsInFrame.data[sourceIndex + 2];
                frameImage.data[destinationIndex + 3] = pixelsInFrame.data[sourceIndex + 3];
            }
        }

        let torsoMinX = spriteWidth;
        let torsoMaxX = 0;
        const torsoStart = Math.floor(spriteHeight * 0.45);
        for (let y = torsoStart; y < spriteHeight; y += 1) {
            for (let x = 0; x < spriteWidth; x += 1) {
                if (frameImage.data[((y * spriteWidth + x) * 4) + 3] > 20) {
                    torsoMinX = Math.min(torsoMinX, x);
                    torsoMaxX = Math.max(torsoMaxX, x);
                }
            }
        }

        const localTorsoCenterX = (torsoMinX + torsoMaxX) / 2;
        const scale = targetHeight / spriteHeight;
        const scaledWidth = Math.round(spriteWidth * scale);
        const scaledHeight = Math.round(spriteHeight * scale);
        const sourceSprite = document.createElement('canvas');
        sourceSprite.width = spriteWidth;
        sourceSprite.height = spriteHeight;
        const sourceSpriteContext = sourceSprite.getContext('2d');
        sourceSpriteContext.imageSmoothingEnabled = false;
        sourceSpriteContext.putImageData(frameImage, 0, 0);
        frameContext.putImageData(
            frameContext.createImageData(96, 96),
            0,
            0,
        );
        frameContext.drawImage(
            sourceSprite,
            0,
            0,
            spriteWidth,
            spriteHeight,
            Math.round(48 - (localTorsoCenterX * scale)),
            Math.round(92 - scaledHeight),
            scaledWidth,
            scaledHeight,
        );
        return frameCanvas;
    };

    const keyCanvas = (image, labelCutoff = 0) => {
        const source = document.createElement('canvas');
        source.width = image.naturalWidth;
        source.height = image.naturalHeight;
        const sourceContext = source.getContext('2d', { willReadFrequently: true });
        sourceContext.imageSmoothingEnabled = false;
        sourceContext.drawImage(image, 0, 0);
        const imageData = sourceContext.getImageData(0, 0, source.width, source.height);
        const pixels = imageData.data;
        const background = {
            red: pixels[0],
            green: pixels[1],
            blue: pixels[2],
        };

        for (let index = 0; index < pixels.length; index += 4) {
            const x = (index / 4) % source.width;
            const red = pixels[index];
            const green = pixels[index + 1];
            const blue = pixels[index + 2];
            const redDelta = red - background.red;
            const greenDelta = green - background.green;
            const blueDelta = blue - background.blue;
            const distance = Math.sqrt(
                (redDelta * redDelta) + (greenDelta * greenDelta) + (blueDelta * blueDelta),
            );

            if (x < labelCutoff || distance < 40) {
                pixels[index + 3] = 0;
            } else if (distance < 55) {
                const alpha = ((distance - 40) / 15) * 255;
                const edgeFactor = alpha / 255;
                pixels[index] = Math.round(red * edgeFactor);
                pixels[index + 1] = Math.round(green * edgeFactor);
                pixels[index + 2] = Math.round(blue * edgeFactor);
                pixels[index + 3] = Math.round(alpha);
            }
        }

        sourceContext.putImageData(imageData, 0, 0);
        return { source, sourceContext, pixels };
    };

    const sliceWalkSheet = () => {
        const { source, sourceContext, pixels } = keyCanvas(walkSheet);
        const activeColumns = [];

        for (let x = 0; x < source.width; x += 1) {
            let active = false;
            for (let y = 0; y < source.height && !active; y += 1) {
                if (pixels[((y * source.width + x) * 4) + 3] > 20) {
                    active = true;
                }
            }
            if (active) {
                activeColumns.push(x);
            }
        }

        const groups = [];
        let start = activeColumns[0];
        let previous = start;
        activeColumns.slice(1).forEach((column) => {
            if (column - previous > 24) {
                groups.push([start, previous + 1]);
                start = column;
            }
            previous = column;
        });
        if (start !== undefined) {
            groups.push([start, previous + 1]);
        }

        const walkFrames = groups.map(([left, right]) => {
            const framePixels = sourceContext.getImageData(left, 0, right - left, source.height);
            return {
                left,
                right,
                pixels: framePixels,
                width: right - left,
                height: source.height,
            };
        }).filter((group) => {
            let minX = group.width;
            let minY = group.height;
            let maxX = 0;
            let maxY = 0;
            for (let y = 0; y < group.height; y += 1) {
                for (let x = 0; x < group.width; x += 1) {
                    if (group.pixels.data[((y * group.width + x) * 4) + 3] > 20) {
                        minX = Math.min(minX, x);
                        minY = Math.min(minY, y);
                        maxX = Math.max(maxX, x);
                        maxY = Math.max(maxY, y);
                    }
                }
            }
            return maxX - minX >= 20 && maxY - minY >= 20;
        }).slice(0, 6);

        frames[1] = walkFrames.map((frame) => (
            normalizeFrame(sourceContext, frame.pixels, 0, 0, frame.width, frame.height)
        )).filter(Boolean);
        walkReady = frames[1].length === 6;
        maybeStart();
    };

    const maybeStart = () => {
        if (initialized || !mainReady || !walkReady) {
            return;
        }
        initialized = true;
        const first = choosePlatform();
        if (first) {
            state.x = first.left + first.width / 2;
            landOn(first);
            state.actionStartedAt = performance.now();
            setState('IDLE');
            window.requestAnimationFrame(tick);
        }
    };

    const sliceSpriteSheet = () => {
        const labelCutoff = Math.floor(spriteSheet.naturalWidth * 0.16);
        const { source, sourceContext, pixels } = keyCanvas(spriteSheet, labelCutoff);
        const rowHeight = source.height / 5;

        for (let row = 0; row < 5; row += 1) {
            const top = Math.floor(row * rowHeight);
            const bottom = Math.floor((row + 1) * rowHeight);
            const activeColumns = [];
            for (let x = labelCutoff; x < source.width; x += 1) {
                let active = false;
                for (let y = top; y < bottom && !active; y += 1) {
                    if (pixels[((y * source.width + x) * 4) + 3] > 20) {
                        active = true;
                    }
                }
                if (active) {
                    activeColumns.push(x);
                }
            }

            const frameCount = rowFrameCounts[row];
            const firstColumn = activeColumns[0];
            const lastColumn = activeColumns[activeColumns.length - 1];
            let centers = Array.from({ length: frameCount }, (_, index) => (
                firstColumn + ((lastColumn - firstColumn) * (index + 0.5) / frameCount)
            ));

            for (let iteration = 0; iteration < 8; iteration += 1) {
                const buckets = centers.map(() => []);
                activeColumns.forEach((column) => {
                    const nearest = centers.reduce((best, center, index) => (
                        Math.abs(column - center) < Math.abs(column - centers[best]) ? index : best
                    ), 0);
                    buckets[nearest].push(column);
                });
                centers = buckets.map((bucket, index) => (
                    bucket.length ? bucket.reduce((sum, column) => sum + column, 0) / bucket.length : centers[index]
                ));
            }

            const groups = centers.map((center, index) => {
                const previousCenter = index ? centers[index - 1] : labelCutoff;
                const nextCenter = index < centers.length - 1 ? centers[index + 1] : source.width;
                const left = Math.max(labelCutoff, Math.floor((previousCenter + center) / 2));
                const right = Math.min(source.width, Math.ceil((center + nextCenter) / 2));
                return [left, right];
            });

            groups.forEach(([left, right]) => {
                const pixelsInFrame = sourceContext.getImageData(left, top, right - left, bottom - top);
                let minX = right - left;
                let minY = bottom - top;
                let maxX = 0;
                let maxY = 0;
                for (let y = 0; y < bottom - top; y += 1) {
                    for (let x = 0; x < right - left; x += 1) {
                        if (pixelsInFrame.data[((y * (right - left) + x) * 4) + 3] > 0) {
                            minX = Math.min(minX, x);
                            minY = Math.min(minY, y);
                            maxX = Math.max(maxX, x);
                            maxY = Math.max(maxY, y);
                        }
                    }
                }
                const width = maxX - minX + 1;
                const height = maxY - minY + 1;
                const frameImage = sourceContext.getImageData(left + minX, top + minY, width, height);
                const frameCanvas = normalizeFrame(
                    sourceContext,
                    frameImage,
                    0,
                    0,
                    width,
                    height,
                );
                frames[row].push(frameCanvas);
            });
        }

        mainReady = frames.every((row, rowIndex) => row.length >= rowFrameCounts[rowIndex]);
        maybeStart();
    };

    document.addEventListener('click', (event) => {
        const target = event.target.closest('a, button, .project-card, [data-platform]');
        if (!target || target === npc || npc.contains(target)) {
            return;
        }
        seek(target);
    });

    window.addEventListener('resize', () => {
        if (state.platform) {
            state.platform = pageBounds(state.platform.element);
        }
        render();
    });

    spriteSheet.addEventListener('load', sliceSpriteSheet, { once: true });
    walkSheet.addEventListener('load', sliceWalkSheet, { once: true });
    spriteSheet.src = '/assets/portfolio-buddy-sheet.jpg';
    walkSheet.src = '/images/buddy-walk.png';
}
